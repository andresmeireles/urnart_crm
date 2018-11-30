<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', TextType::class, [
                'label' => 'Nome Do Cliente'
            ])
            ->add('products', CollectionType::class, [
                'label' => 'Produtos'
            ])
            ->add('freight', NumberType::class, [
                'label' => 'Freight'
            ])
            ->add('discount', NumberType::class, [
                'label' => 'Discount'
            ])
            ->add('total', NumberType::class, [
                'label' => 'Total Price'
            ])
            ->add('paymentType', TextType::class, [
                'label' => 'Paymeny Type'
            ])
            ->add('installment', IntegerType::class, [
                'label' => 'Number of installment'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Info :)'
            ]);
    }
}
