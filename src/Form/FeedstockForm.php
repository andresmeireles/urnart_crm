<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\Feedstock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\FeedstockInventory;

class FeedstockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $data): void
    {
        $builder
            ->add('nome')
            ->add('description')
            ->add('vendors', CollectionType::class)
            ->add('feedstockInventory')
            ->add('periodicity')
            ->add('maxStock')
            ->add('minStock')
            ->add('unit')
            ->add('departament')
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Feedstock::class,
        ));
    }
}