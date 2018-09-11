<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductCart as Cart;
use App\Entity\ProductInventory;
use App\Entity\PessoaJuridica;

class OrderModel extends Model
{
    public function createOrder(array $information, array $products): array
    {
        $em = $this->em;

        $em->getConnection()->beginTransaction();

        try {
            $order = new Order();
            $totalPrice = 0;

            $customer = $this->em->getRepository(PessoaJuridica::class)->find($information['clientName']);

            $order->setCustomer($customer);

            $freight = $information['freight'] === '' ? null : (float) $information['freight'];
            $order->setFreight($freight);

            $discount = $information['discount'] === '' ? null : (float) $information['discount'];
            $order->setDiscount($discount);
            
            $order->setPaymentType(21);
            $order->setInstallment(1);
            //$order->setComments($information['observation']);
            $order->setComments(null);
            $order->setTransporter(null);

            foreach ($products as $product) {
                $cart = new Cart();

                $cart->setOrderNumber($order);
                
                $existentProduct = $this->em->getRepository(Product::class)->find($product['cod']);
                $cart->setProduct($existentProduct);

                $amount = (int) $product['qnt'];
                $cart->setAmount($amount);
                
                $price = $product['price'] === '' ? $existentProduct->getPrice() : (float) $product['price'];
                if ($existentProduct->getPrice() !== $price) {
                    $cart->setCustomPrice($price);
                }
                
                $em->persist($cart);
                //$this->createCart($order, $products);
                $totalPrice += ( (float) $product['price'] * (float) $product['qnt']); 
            }

            $order->setTotalPrice($totalPrice);

            $em->persist($order);
            $em->flush();
            
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            return array(
                'http_code' => 401,
                'message' => "{$e->getMessage()}"
            );
        }

        return array(
            'http_code' => 301,
            'message' => "Pedido {$order->getId()} criado com sucesso"
        );
    }

    public function createCart(Order $order, array $products): void
    {
        $em = $this->em;

        foreach($products as $product) {
            try {
                $cart = new Cart();

                $cart->setOrderNumber($order);
                
                $existentProduct = $this->em->getRepository(Product::class)->find($product['cod']);
                $cart->setProduct($existentProduct);

                $amount = (int) $product['qnt'];
                $cart->setAmount($amount);
                
                $price = $product['price'] === '' ? $existentProduct->getPrice() : (float) $product['price'];
                if ($existentProduct->getPrice() !== $price) {
                    $cart->setCustomPrice($product['price']);
                }
                
                $em->persist($cart);
                $em->flush();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage().'. Arquivo -> '.$e->getFile().'. Linha ->'.$e->getLine());
            }
        }
    }
}