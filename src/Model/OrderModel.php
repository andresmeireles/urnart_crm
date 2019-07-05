<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Entity\Order;
use App\Entity\PaymentType;
use App\Entity\PessoaJuridica;
use App\Entity\Product;
use App\Entity\ProductCart as Cart;
use App\Entity\Transporter;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\StringConvertions;

class OrderModel extends Model
{
    /**
     * @param array $information
     * @param array $products
     * @param string $type
     * @throws \Exception
     * @return FlashResponse
     */
    public function executeActionOnOrder(array $information, array $products, string $type = 'insert'): FlashResponse
    {
        $entityManager = $this->em;
        //$entityManager->getConnection()->beginTransaction();
        try {
            if ($type == 'update') {
                $id = $information['id'];
                $order = $entityManager->getRepository(Order::class)->find($id);
                if ($order->isClosed()) {
                    return new FlashResponse(400, 'warning', 'Pedido fechado não pode ser editado');
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
                $customPort = ltrim(trim($information['port'])) === $transporter->getPort() ?
                null :
                ltrim(trim($information['port']));
            }
            $order->setCustomPort($customPort);
            $paymentType = $this->em->getRepository(PaymentType::class)->find($information['formPg']);
            $order->setPaymentType($paymentType);
            $installments = array_key_exists('installments', $information) ? (int) $information['installments'] : null;
            $order->setInstallment($installments);
            $comments = trim(ltrim(filter_var($information['observation'], FILTER_SANITIZE_STRING)));
            $order->setComments($comments);
            if ($type == 'update' && isset($id)) {
                $productCart = $entityManager->getRepository(Cart::class)
                                            ->findBy(['orderNumber' => $id]);
                foreach ($productCart as $p) {
                    $entityManager->remove($p);
                }
            }
            foreach ($products as $product) {
                $cart = new Cart();
                $existentProduct = $this->em->getRepository(Product::class)->find($product['cod']);
                if (is_null($existentProduct)) {
                    return new FlashResponse(
                        400,
                        'error',
                        sprintf(
                            'Erro. Produto %s não existe ou de produto não identificado',
                            $product['product']
                        )
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
            //$entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            // development message
            //throw new \Exception(sprintf('%s %s %s', $e->getMessage(), $e->getFile(), $e->getLine()));
            //$entityManager->getConnection()->rollback();

            return new FlashResponse(401, 'error', $e->getMessage());
        }

        return new FlashResponse(301, 'error', sprintf("Pedido %s criado com sucesso", $order->getId()));
    }

    /**
     * @param array $information
     * @param array $products
     * @throws \Exception
     * @return FlashResponse
     */
    public function updateOrder(array $information, array $products): FlashResponse
    {
        return $this->executeActionOnOrder($information, $products, 'update');
    }

    /**
     * @param array $information
     * @param array $products
     * @throws \Exception
     * @return FlashResponse
     */
    public function createOrder(array $information, array $products): FlashResponse
    {
        return $this->executeActionOnOrder($information, $products, 'insert');
    }

    /**
     * @param int $orderId
     * @throws \Exception
     * @return FlashResponse
     */
    public function reserve(int $orderId): FlashResponse
    {
        if (!$this->settings->getProperty('allow_reserve')) {
            return new FlashResponse(400, 'warning', 'Operação desabilitada pelo sistema.');
        }
        $entityManager = $this->em;
        //$entityManager->getConnection()->beginTransaction();
        $order = $entityManager->getRepository(Order::class)->find($orderId);
        if (!$order->isOpen()) {
            $message = $order->isReserved() ?
            sprintf("Pedido %s já tem os produtos reservados", $order->getId()) :
            sprintf("Pedido %s já foi fechado e não pode ser modificado", $order->getId());

            return new FlashResponse(400, 'error', $message);
        }
        try {
            $productCarts = $order->getProductCarts();
            $order->reserve();
            foreach ($productCarts as $cart) {
                $product = $cart->getProduct();
                if (!$this->settings->getProperty('allow_negative_stock')) {
                    if ($product->getStock() < $cart->getAmount()) {
                        $entityManager->getConnection()->rollback();
                        
                        return new FlashResponse(
                            400,
                            'error',
                            sprintf(
                                "Produto %s não tem estoque suficiente para reservar.",
                                $product->getName()
                            )
                        );
                    }
                }
                $reserved = $product->getProductInventory()->getReserved();
                $amountReserved = $reserved + $cart->getAmount();
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

        return new FlashResponse(
            301,
            'success',
            sprintf(
                "Produtos do pedido %s foram reservados com sucesso",
                $orderId
            )
        );
    }

    /**
     * @param int|null $id
     * @param string $hash
     * @param string $type
     * @throws \Exception
     * @return FlashResponse
     */
    public function runUnreserveTypeAction(?int $id, string $hash, string $type = 'open'): FlashResponse
    {
        $entityManager = $this->em;
        if (!$this->validate($hash) || is_null($id)) {
            return new FlashResponse(400, 'error', 'erro, operação não autorizada...');
        }
        //$entityManager->getConnection()->beginTransaction();
        $order = $entityManager->getRepository(Order::class)->find($id);
        if ($type == 'open' && $order->isOpen()) {
            return new FlashResponse(400, 'error', sprintf("Pedido %s já está aberto...", $id));
        }
        if ($type == 'close' && $order->isClosed()) {
            $order->setAtive(false);
            return new FlashResponse(400, 'error', sprintf("Pedido %s já está fechado...", $id));
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
                $amountReserved = $reserved - $cart->getAmount();
                $product->setReserved($amountReserved);
                $entityManager->merge($product);
            }
            $entityManager->merge($order);
            $entityManager->flush();
            //$entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            //$entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
        }
        $message = $order->isClosed() ?
                    sprintf("Pedido %s fechado com sucesso", $id) :
                    sprintf("Produtos do pedido %s foi aberto novamento", $id);

        return new FlashResponse(301, 'success', $message);
    }

    /**
     * @param int $orderId
     * @param string $hash
     * @throws \Exception
     * @return FlashResponse
     */
    public function radiateOrder(int $orderId, string $hash): FlashResponse
    {
        return $this->runUnreserveTypeAction($orderId, $hash, 'open');
    }

    /**
     * @param int $orderId
     * @param string $hash
     * @throws \Exception
     * @return FlashResponse
     */
    public function closeOrder(int $orderId, string $hash): FlashResponse
    {
        return $this->runUnreserveTypeAction($orderId, $hash, 'close');
    }

    /**
     * @param int $orderId
     * @return FlashResponse
     */
    public function removeOrder(int $orderId): FlashResponse
    {
        $removeOrder = $this->em->getRepository(Order::class)->find($orderId);
        $order = $removeOrder->getId();
        if (is_null($removeOrder)) {
            return new FlashResponse(200, 'error', 'Erro interno');
        }
        $cartsToRemove = $removeOrder->getProductCarts();
        //$this->em->getConnection()->beginTransaction();
        try {
            foreach ($cartsToRemove as $cart) {
                $this->em->remove($cart);
            }
            $this->em->remove($removeOrder);
            $this->em->flush();
            //$this->em->getConnection()->commit();
            return new FlashResponse(200, 'warning', sprintf("Pedido %s removido com sucesso!", $orderId));
        } catch (\Exception $e) {
            //$this->em->getConnection()->rollback();
            return new FlashResponse(200, 'danger', sprintf("Erro ao remove pedido %s.", $order));
        }
    }

    /**
     * @param string|null $hash
     * @param string $message
     * @return bool
     */
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

    /**
     * @param array $orderData
     * @param array $products
     * @return FlashResponse
     * @throws \Exception
     */
    public function createManualReport(array $orderData, array $products)
    {
        $entityManager = $this->em;
        $entityManager->getConnection()->beginTransaction();
        try {
            $report = new ManualOrderReport();
            $report->setCustomerName($orderData['clientName']);
            $report->setCustomerCity($orderData['clientCity']);
            $discount = (float) trim(str_replace('R$', '', $orderData['discount']));
            $report->setDiscount($discount);
            $freight = (float) trim(str_replace('R$', '', $orderData['freight']));
            $report->setFreight($freight);
            $report->setTransporter($orderData['transporter']);
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
     * @param array $orderData
     * @param array $products
     * @param int $orderId
     * @throws \Exception
     * @return FlashResponse
     */
    public function editManualOrder(array $orderData, array $products, int $orderId): FlashResponse
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
            $report->setTransporter($orderData['transporter']);
            $port = $orderData['port'] === '' ? null : $orderData['port'];
            $report->setPort($port);
            $observation = $orderData['observation'] === '' ? null : $orderData['observation'];
            $report->setComments($observation);
            $paymentType = $entityManager->getRepository(PaymentType::class)->find($orderData['formPg']);
            $report->setPaymentType($paymentType);
            $removeCart = $entityManager->getRepository(ManualProductCart::class)
                                ->findBy(['manualOrderReport' => $orderId]);
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

    /**
     * @param array $product
     * @param ManualOrderReport $report
     * @return ManualProductCart
     */
    public function actionOnManualCart(array $product, ManualOrderReport $report): ManualProductCart
    {
        $cart = new ManualProductCart();
        $cart->setProductName($product['model']);
        $cart->setProductPrice((new StringConvertions())->moneyToFloat($product['money']));
        $cart->setProductAmount((int) $product['amount']);
        $cart->setManualOrderReport($report);
        return $cart;
    }

    /**
     * @param ManualOrderReport $orderReport
     * @return OrderModel
     */
    public function closeManualOrder(ManualOrderReport $orderReport): self
    {
        $orderReport->setActive(false);
        $this->em->merge($orderReport);
        $this->em->flush();

        return $this;
    }

    /**
     * @param array $cart
     * @return OrderModel
     * @throws \Exception
     */
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

    /**
     * @param ManualOrderReport $object
     * @return OrderModel
     * @throws \Exception
     */
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
