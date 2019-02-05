<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PaymentType;
use App\Entity\Transporter;
use App\Entity\PessoaJuridica;
use App\Entity\ProductInventory;
use App\Entity\ProductCart as Cart;
use App\Utils\Andresmei\FlashResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Yaml\Yaml;
use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Utils\Andresmei\StringConvertions;

class OrderModel extends Model
{
    /**
     * Execute ação de criacao ou atualização de ordem de serviço e carrinho.
     *
     * @param array $information
     * @param array $products
     * @param string $type
     * @throws \Exception
     * 
     * @return array
     */
    public function executeActionOnOrder(array $information, array $products, string $type = 'insert'): array
    {
        $entityManager = $this->em;
        $entityManager->getConnection()->beginTransaction();
        try {
            if ($type == 'update') {
                $id = $information['id'];
                $order = $entityManager->getRepository(Order::class)->find($id);
                if ($order->isClosed()) {
                    return FlashResponse::response(400, 'warning', 'Pedido fechado não pode ser editado');
                }
            } else {
                $order = new Order();
            }
            $totalPrice = 0;
            $customer = $this->em->getRepository(PessoaJuridica::class)->find($information['clientName']);
            $order->setCustomer($customer);
            $freight = $information['freight'] === '' ? null : (float) $information['freight'];
            $order->setFreight($freight);
            $discount = $information['discount'] === '' ? null : (float) $information['discount'];
            $order->setDiscount($discount);
            $transporter = $this->em->getRepository(Transporter::class)->find($information['transporter']);
            $transporter = $transporter ?? null;
            $order->setTransporter($transporter);
            $customPort = array_key_exists('port', $information) ? $information['port'] : null;
            if (!is_null($transporter) && !is_null($customPort)) {
                $customPort = (ltrim(trim($information['port'])) === $transporter->getPort()) ? null : ltrim(trim($information['port']));
            }
            $order->setCustomPort($customPort);
            $paymentType = $this->em->getRepository(PaymentType::class)->find($information['formPg']);
            $order->setPaymentType($paymentType);
            $installments = array_key_exists('installments', $information) ? (int) $information['installments'] : null;
            $order->setInstallment($installments);
            $comments = trim(ltrim(filter_var($information['observation'], FILTER_SANITIZE_STRING)));
            $order->setComments($comments);
            if ($type == 'update' && isset($id)) {
                $productCart = $entityManager->getRepository(Cart::class)->findBy(array(
                'orderNumber' => $id
              ));
                foreach ($productCart as $p) {
                    $entityManager->remove($p);
                }
            }
            foreach ($products as $product) {
                $cart = new Cart;
                $existentProduct = $this->em->getRepository(Product::class)->find($product['cod']);
                if (is_null($existentProduct)) {
                    return array(
                        'http_code' => 400,
                        'message' => 'Erro. Produto '. $product['product'] .' não existe ou de produto não identificado'
                    );
                }
                $cart->setProduct($existentProduct);
                $amount = (int) $product['qnt'];
                $cart->setAmount($amount);
                $price = array_key_exists('price', $product) ? (float) $product['price'] : $existentProduct->getPrice();
                if ($existentProduct->getPrice() !== $price) {
                    $cart->setCustomPrice($price);
                }
                $cart->setOrderNumber($order);
                $entityManager->persist($cart);
                $totalPrice += ($price * (float) $product['qnt']);
            }
            $order->setTotalPrice($totalPrice);
            if ($type == 'update') {
                $entityManager->merge($order);
            } else {
                $order->setActive(true);
                $entityManager->persist($order);
            }
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            // development message
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
            $entityManager->getConnection()->rollback();
            return array(
                'http_code' => 401,
                'message' => $e->getMessage()
            );
        }
        return array(
            'http_code' => 301,
            'message' => "Pedido {$order->getId()} criado com sucesso"
        );
    }

    /**
     * Chama função executeActionOnOrder com ação update
     *
     * @param array $information
     * @param array $products
     * @throws \Exception
     * 
     * @return array
     */
    public function updateOrder(array $information, array $products): array
    {
        return $this->executeActionOnOrder($information, $products, 'update');
    }

    /**
     * Chama função executeActionOnOrder com ação insert
     *
     * @param array $information
     * @param array $products
     * 
     * @throws \Exception
     * 
     * @return array
     */
    public function createOrder(array $information, array $products): array
    {
        return $this->executeActionOnOrder($information, $products, 'insert');
    }

    /**
     * Reserve products in cart or irder number.
     *
     * @param  int   $orderId [description]
     * 
     * @throws \Exception
     * 
     * @return array     [description]
     */
    public function reserve(int $orderId): array
    {
        if (!$this->settings->getProperty('allow_reserve')) {
            return array(
              'http_code' => 400,
              'type' => 'warning',
              'message' => 'Operação desabilitada pelo sistema.'
            );
        }
        $entityManager = $this->em;
        $entityManager->getConnection()->beginTransaction();
        $order = $entityManager->getRepository(Order::class)->find($orderId);
        if (!$order->isOpen()) {
            $message = $order->isReserved() ? "Pedido {$order->getId()} já tem os produtos reservados" : "Pedido {$order->getId()} já foi fechado e não pode ser modificado";
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => $message
            );
        }
        try {
            $productCarts = $order->getProductCarts();
            $order->reserve();
            foreach ($productCarts as $cart) {
                $product = $cart->getProduct();
                if (!$this->settings->getProperty('allow_negative_stock')) {
                    if ($product->getStock() < $cart->getAmount()) {
                        $entityManager->getConnection()->rollback();
                        return array(
                        'http_code' => 400,
                        'type' => 'error',
                        'message' => "Produto {$product->getName()} não tem estoque suficiente para reservar."
                    );
                    }
                }
                $reserved = $product->getProductInventory()->getReserved();
                $amountReserved = ($reserved + $cart->getAmount());
                $product->setReserved($amountReserved);
                $entityManager->merge($product);
            }
            $entityManager->merge($order);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
        }
        return array(
          'http_code' => 301,
          'type' => 'success',
          'message' => "Produtos do pedido {$orderId} foram reservados com sucesso"
        );
    }

    /**
     * @param int|null $id
     * @param string $hash
     * @param string $type
     * 
     * @throws \Exception
     * 
     * @return array
     */
    public function runUnreserveTypeAction(?int $id, string $hash, string $type = 'open'): array
    {
        $entityManager = $this->em;
        if (!$this->validate($hash) || is_null($id)) {
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => 'erro, operação não autorizada...'
            );
        }
        $entityManager->getConnection()->beginTransaction();
        $order = $entityManager->getRepository(Order::class)->find($id);
        if ($type == 'open' && $order->isOpen()) {
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => "Pedido {$id} já está aberto..."
            );
        }
        if ($type == 'close' && $order->isClosed()) {
            $order->setAtive(false);
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => "Pedido {$id} já está fechado..."
            );
        }
        try {
            $productCarts = $order->getProductCarts();
            $actualStatus = $order->getReserved();
            $order->reOpen();
            if ($type == 'close') {
                $order->close();
            }
            foreach ($productCarts as $cart) {
                $product = $cart->getProduct();
                $reserved = $product->getProductInventory()->getReserved();
                if ($actualStatus !== 1) {
                    continue;
                }
                $amountReserved = ($reserved - $cart->getAmount());
                $product->setReserved($amountReserved);
                $entityManager->merge($product);
            }
            $entityManager->merge($order);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
        }
        $message = $order->isClosed() ? "Pedido {$id} fechado com sucesso" : "Produtos do pedido {$id} foi aberto novamento";
        return array(
            'http_code' => 301,
            'type' => 'success',
            'message' => $message
        );
    }

    /**
     * @param int $orderId
     * @param string $hash
     * @throws \Exception
     * @return array
     */
    public function radiateOrder(int $orderId, string $hash): array
    {
        return $this->runUnreserveTypeAction($orderId, $hash, 'open');
    }

    /**
     * @param int $orderId
     * @param string $hash
     * 
     * @return array
     * @throws \Exception
     */
    public function closeOrder(int $orderId, string $hash): array
    {
        return $this->runUnreserveTypeAction($orderId, $hash, 'close');
    }

    /**
     * @param int $orderId
     * 
     * @return array
     */
    public function removeOrder(int $orderId): array
    {
        $removeOrder = $this->em->getRepository(Order::class)->find($orderId);
        $order = $removeOrder->getId();
        if (is_null($removeOrder)) {
            return array('type' => 'error', 'message' => 'Erro interno');
        }
        $cartsToRemove = $removeOrder->getProductCarts();
        $this->em->getConnection()->beginTransaction();
        try {
            foreach ($cartsToRemove as $cart) {
                $this->em->remove($cart);
            }
            $this->em->remove($removeOrder);
            $this->em->flush();
            $this->em->getConnection()->commit();
            return array('http_code' => '200', 'type' => 'warning', 'message' => "Pedido {$orderId} removido com sucesso!");
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            return array('type' => 'danger', 'message' => "Erro ao remove pedido {$order}.");
        }
    }

    public function validate(?string $hash, string $message = 'valido'): bool
    {
        $trueHash = hash('ripemd160', $message);
        if (is_null($hash) || $hash !== $trueHash) {
            return false;
        }
        return true;
    }

    /***************************************************************
     ******************** MANUAL REPORT CREATION *******************
     ***************************************************************/
    

     public function createManualReport(array $orderData, array $products)
    {
        $entityManager = $this->em;
        
        $entityManager->getConnection()->beginTransaction();

        try {
            $report = new ManualOrderReport;
            $report->setCustomerName($orderData['clientName']);
            $report->setCustomerCity($orderData['clientCity']);
            $discount = (float) trim(str_replace('R$', '', $orderData['discount']));
            $report->setDiscount($discount);
            $freight = (float) trim(str_replace('R$', '', $orderData['freight']));
            $report->setFreight($freight);
            $transporter = $orderData['transporter'] === '' ? null : $entityManager->getRepository(Transporter::class)->find($orderData['transporter']);
            $report->setTransporter($transporter);
            $port = $orderData['port'] === '' ? null : $orderData['port'];
            $report->setPort($port);
            $observation = $orderData['observation'] === '' ? null : $orderData['observation'];
            $report->setComments($observation);
            $paymentType = $entityManager->getRepository(PaymentType::class)->find($orderData['formPg']);
            $report->setPaymentType($paymentType);
            foreach ($products as $product) {
                $cart = $this->actionOnManualCart($product, $report);
                $entityManager->persist($cart);
            }
            $entityManager->persist($report);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage());
        }

        return new FlashResponse(200, 'success', 'Sucesso!');
    }
    
    /**
     * Edit order by id in database.
     * 
     * @param array $orderData
     * @param array $products
     * @param int $orderId
     * @throws \Exception
     */
    public function editManualOrder(array $orderData, array $products, int $orderId)
    {
        $entityManager = $this->em;
        
        $entityManager->getConnection()->beginTransaction();
        
        try {
            $report = $entityManager->getRepository(ManualOrderReport::class)->find($orderId);
            $report->setCustomerName($orderData['clientName']);
            $report->setCustomerCity($orderData['clientCity']);
            $discount = (float) trim(str_replace('R$', '', $orderData['discount']));
            $report->setDiscount($discount);
            $freight = (float) trim(str_replace('R$', '', $orderData['freight']));
            $report->setFreight($freight);
            $transporter = $orderData['transporter'] === '' ? null : $entityManager->getRepository(Transporter::class)->find($orderData['transporter']);
            $report->setTransporter($transporter);
            $port = $orderData['port'] === '' ? null : $orderData['port'];
            $report->setPort($port);
            $observation = $orderData['observation'] === '' ? null : $orderData['observation'];
            $report->setComments($observation);
            $paymentType = $entityManager->getRepository(PaymentType::class)->find($orderData['formPg']);
            $report->setPaymentType($paymentType);
            
            $removeCart = $entityManager->getRepository(ManualProductCart::class)->findBy(array('manualOrderReport' => $orderId));
            $this->removeManualProductCart($removeCart);
            
            foreach ($products as $product) {
                $cart = $this->actionOnManualCart($product, $report);
                $entityManager->merge($cart);
            }
            $entityManager->merge($report);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage());
        }
        
        return new FlashResponse(200, 'success', sprintf('Pedido %d alterado com sucesso.', $orderId));
    }

    public function actionOnManualCart(array $product, ManualOrderReport $report): ManualProductCart
    {        
        $cart = new ManualProductCart();
        $cart->setProductName($product['model']);
        $cart->setProductPrice( (new StringConvertions())->moneyToFloat($product['money']) );
        $cart->setProductAmount( (int) $product['amount']);
        $cart->setManualOrderReport($report);
        return $cart;
    }

    public function closeManualOrder(ManualOrderReport $orderReport): self
    {
        $orderReport->setActive(false);
        $this->em->merge($orderReport);
        $this->em->flush();

        return $this;
    }

    public function removeManualProductCart(array $cart): self
    {
        try {
            foreach ($cart as $product) {
                $this->em->remove($product);
            }
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    public function removeManualOrder(ManualOrderReport $object): self
    {
        try {
            $this->em->remove($object);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }
}
