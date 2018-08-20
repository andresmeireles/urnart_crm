<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\Feedstock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FeedstockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $data): void
    {
        $builder
            ->add('nome', TextType::class, array(
                'label' => 'Nome',
                'required' => true
            ))
            ->add('description')
            ->add('vendors', CollectionType::class)
            ->add('feedstockInventory')
            ->add('periodicity')
            ->add('maxStock', NumberType::class, array(
                'label' => 'Estoque Máximo',
                'required' => false
            ))
            ->add('minStock', NumberType::class, array(
                'label' => 'Estoque Minimo',
                'required' => true
            ))
            ->add('unit')
            ->add('departament')
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-success'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Feedstock::class,
        ));
    }
}