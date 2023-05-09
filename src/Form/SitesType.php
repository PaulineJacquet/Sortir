<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => false,
                'empty_data' => '',
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'customBTN']
            ]);
    }
}