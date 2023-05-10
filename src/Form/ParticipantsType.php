<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ParticipantsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('nom', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('telephone', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => false,
                'empty_data' => '',
            ])
            ->add('mail', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('password', TextType::class, [
                'label' => false,
                'trim' => true,
                'required' => true,
                'empty_data' => '',
            ])
            ->add('administrateur', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('actif', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('site', IntegerType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'customBTN']
            ]);
    }

}