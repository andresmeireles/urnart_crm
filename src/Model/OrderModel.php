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
use Doctrine\Common\Collections\ArrayCollection;

class OrderModel extends Model
{
    /**
     * Execute ação de criacao ou atualização de ordem de serviço e carrinho.
     *
     * @param array $information
     * @param array $products
     * @param string $type
     * @return array
     */
    public function executeActionOnOrder(array $information, array $products, string $type = 'insert'): array
    {
        $em = $this->em;
        $em->getConnection()->beginTransaction();
        try {

            if ($type == 'update') {
                $id = $information['id'];
                $order = $em->getRepository(Order::class)->find($id);
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

            if ($type == 'update') {
              $productCart = $em->getRepository(Cart::class)->findBy(array(
                'orderNumber' => $id
              ));
              foreach ($productCart as $p) {
                $em->remove($p);
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
                $em->persist($cart);

                $totalPrice += ( $price * (float) $product['qnt']);
            }

            $order->setTotalPrice($totalPrice);

            if ($type == 'update') {
                $em->merge($order);
            } else {
                $order->setActive(true);
                $em->persist($order);
            }

            $em->flush();

            $em->getConnection()->commit();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
            $em->getConnection()->rollback();
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
     * @return void
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
     * @return void
     */
    public function createOrder(array $information, array $products): array
    {
        return $this->executeActionOnOrder($information, $products, 'insert');
    }

    /**
     * Reserve products in cart or irder number.
     *
     * @param  int   $id [description]
     * @return array     [description]
     */
    public function reserve(int $id): array
    {
        $em = $this->em;
        $em->getConnection()->beginTransaction();
        $order = $em->getRepository(Order::class)->find($id);
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
            if ($product->getStock() < $cart->getAmount()) {
              $em->getConnection()->rollback();
              return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => "Produto {$product->getName()} não tem estoque suficiente para reservar."
              );
            }
            $reserved = $product->getProductInventory()->getReserved();
            $amountReserved = ($reserved + $cart->getAmount());
            $product->setReserved($amountReserved);
            $em->merge($product);
          }
          $em->merge($order);
          $em->flush();
          $em->getConnection()->commit();
        } catch (\Exception $e) {
          $em->getConnection()->rollback();
          throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
        }
        return array(
          'http_code' => 301,
          'type' => 'success',
          'message' => "Produtos do pedido {$id} foram reservados com sucesso"
        );
    }

    public function runUnreserveTypeAction(?int $id, string $hash, string $type = 'open'): array
    {
        $em = $this->em;
        if (!$this->validate($hash) || is_null($id)) {
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => 'erro, operação não autorizada...'
            );
        }
        $em->getConnection()->beginTransaction();
        $order = $em->getRepository(Order::class)->find($id);
        if ($type == 'open' && $order->isOpen()) {
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => "Pedido {$id} já está aberto..."
            );
        }
        if ($type == 'close' && $order->isClosed()) {
            return array(
                'http_code' => 400,
                'type' => 'error',
                'message' => "Pedido {$id} já está fechado..."
            );
            $order->setAtive(false);
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
                $em->merge($product);
            }
            $em->merge($order);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
        }
        $message = $order->isClosed() ? "Pedido {$id} fechado com sucesso" : "Produtos do pedido {$id} foi aberto novamento";
        return array(
            'http_code' => 301,
            'type' => 'success',
            'message' => $message
        );
    }

    public function radiateOrder(int $id, string $hash): array
    {
        return $this->runUnreserveTypeAction($id, $hash, 'open');
    }

    public function closeOrder(int $id, string $hash): array
    {
        return $this->runUnreserveTypeAction($id, $hash, 'close');
    }

    public function removeOrder($id): array
    {
        $removeOrder = $this->em->getRepository(Order::class)->find($id);
        $orderId = $removeOrder->getId();
        if (is_null($removeOrder)) {
            return array(
                'type' => 'error',
                'message' => 'Erro interno'
            );
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
            return array(
                'http_code' => '200',
                'type' => 'warning',
                'message' => "Pedido {$orderId} removido com sucesso!",
            );
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            return array(
                'type' => 'danger',
                'message' => "Erro ao remove pedido {$order->getId()}."
            );
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
}
