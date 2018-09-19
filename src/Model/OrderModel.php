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
            
            $customPort = $information['port'] == '' ? null : $information['port'];
            if (!is_null($transporter)) {
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
                $query = $em->createQuery("DELETE App\Entity\ProductCart c WHERE c.orderNumber = {$id}");    
            };

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
                //$existentProduct->getProductInventory()->setReserved($amount);
                
                $price = $product['price'] === '' ? $existentProduct->getPrice() : (float) $product['price'];
                if ($existentProduct->getPrice() !== $price) {
                    $cart->setCustomPrice($price);
                }

                $cart->setOrderNumber($order);                
                $em->persist($cart);

                $totalPrice += ( (float) $product['price'] * (float) $product['qnt']); 
            }

            dump($order);
            die();
            
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
            throw new \Exception($e->getMessage());
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
                'http' => '200',
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
}