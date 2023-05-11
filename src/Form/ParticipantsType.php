<?php

namespace App\Form;

use App\Entity\Participants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('site', ChoiceType::class, [
                'choices' => $options['sites'],
                'choice_label' => function ($site) {
                    return $site->getNom();
                },
                'placeholder' => 'Sélectionnez un site',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'customBTN']
            ]);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
            'sites' => [],
        ]);
    }

}