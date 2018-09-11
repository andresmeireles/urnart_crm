<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductCart;
use App\Entity\ProductInventory;
use App\Entity\PessoaJuridica;

class OrderModel extends Model
{
    public function createOrder(array $information, array $products)
    {
        dump($information, $products);
        $em = $this->em;

        $em->getConnection()->beginTransaction();

        try {
            $order = new Order();
            $cart = new ProductCart();
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
                
                $cart->setOrderNumber($order);
                
                $existentProduct = $this->em->getRepository(Product::class)->find($product['cod']);
                $cart->addProduct($existentProduct);

                $amount = (int) $product['qnt'];
                $cart->setAmount($amount);
                
                $price = $product['price'] === '' ? $existentProduct->getPrice() : (float) $product['price'];
                if ($existentProduct->getPrice() !== $price) {
                    $cart->setCustomPrice($product['price']);
                }
                
                $em->persist($cart);

                $totalPrice += ($price * $amount);
            }

            $order->setTotalPrice($totalPrice);

            $em->persist($order);
            $em->flush();
            
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            dump($order, $cart);
            throw new \Exception($e->getMessage().' '.$e->getFile().' '.$e->getLine());
        }

        echo 'foi :)';
        die('deu certo meu amiginho');
    }
}