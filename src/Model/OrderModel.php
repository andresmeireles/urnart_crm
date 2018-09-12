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
            
            $comments = trim(ltrim(filter_var($information['observation'], FILTER_SANITIZE_STRING)));
            $order->setComments($comments);
            
            foreach ($products as $product) {
                $cart = new Cart();

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
                
                $price = $product['price'] === '' ? $existentProduct->getPrice() : (float) $product['price'];
                if ($existentProduct->getPrice() !== $price) {
                    $cart->setCustomPrice($price);
                }

                $cart->setOrderNumber($order);
                
                $em->persist($cart);
                $totalPrice += ( (float) $product['price'] * (float) $product['qnt']); 
            }

            $order->setTotalPrice($totalPrice);
            $order->setActive(true);

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

    /**
     * Chama função executeActionOnOrder com ação update
     *
     * @param array $information
     * @param array $products
     * @return void
     */
    public function updateOrder(array $information, array $products): void
    {
        $this->executeActionOnOrder($information, $products, 'update');
    }

    /**
     * Chama função executeActionOnOrder com ação insert
     *
     * @param array $information
     * @param array $products
     * @return void
     */
    public function createOrder(array $information, array $products): void
    {
        $this->executeActionOnOrder($information, $products, 'insert');
    }
}