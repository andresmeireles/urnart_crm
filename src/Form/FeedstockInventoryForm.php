<?php

namespace App\Form;

use App\Entity\FeedstockInventory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FeedstockInventoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $data)
    {
        $builder
            ->add('stock')
            ->add('minStock')
            ->add('maxStock');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => FeedstockInventory::class,
        ));
    }
}