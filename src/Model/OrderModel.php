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
        die();

        $order = new Order();
        $cart = new ProductCart();

        foreach ($products as $product) {
            $product = $this->em->getRespository(Product::class)->find($product['id']);
        }

        $customer = $this->em->getRepository(PessoaJuridica::class)->find($information['clientName']);

        $order->setCustomer($customer);

        $order->setFreight($information['freight']);
        $order->setDiscount($information['discount']);
        //$order->setTransporter($information['transporter']);
        $order->setPaymentType($information['formPg']);
        $order->setComments($information['observation']);


    }
}