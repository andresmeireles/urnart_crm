<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\FeedstockInventory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FeedstockInventoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $data)
    {
        $form
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