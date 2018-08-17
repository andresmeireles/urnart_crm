<?php
declare(strict_type = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FeedstockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $form, array $data): void
    {
        $form
            ->add('nome')
            ->add('description')
            ->add('vendors')
            ->add('periodicity')
            ->add('feed', CollectionType::class, array(
                'entry_type' => FeedstockInventoryForm::class,
                'entry_options' => array('label' => false)
            ))
            ->add('unit')
            ->add('departament')
            ->add('save', SubmitType::class);
    }
}