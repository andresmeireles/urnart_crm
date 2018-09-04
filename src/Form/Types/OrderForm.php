<?php

namespace App\Form\Types;

use Symfony\Component\Validator\Constraints as Assert;

class OrderForm
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * 
     * @var string
     */
    public $customer;

    /**
     * @Assert\Type("array")
     * @Assert\NotBlank()
     *
     * @var string
     */
    public $products;

    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @Assert\Currency
     * 
     * @var float
     */
    public $freight;

    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @Assert\Currency
     *
     * @var float
     */
    public $discount;

    /**
     * @Assert\Type("float")
     * @Assert\NotBlank()
     * @Assert\Currency
     * 
     * @var float
     */
    public $total;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * 
     * @var string
     */
    public $paymentType;

    /**
     * @Assert\Type("int")
     * @Assert\NotBlank()
     * 
     * @var float
     */
    public $installment;
}