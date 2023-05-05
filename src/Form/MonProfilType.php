<?php

namespace App\Form;

use App\Entity\Participants;
use App\Entity\Sites;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'trim' => true,
                'required' => true

            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'trim' => true,
                'required' => true,
                'disabled' => true
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'trim' => true,
                'required' => true,
                'disabled' => true
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'trim' => true
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'trim' => true,
                'required' => true
            ])
            ->add('password',PasswordType::class, [
                'label' => 'Mot de passe',
                'trim' => true,
                'required' => true
            ])
            ->add('confirmPassword',PasswordType::class, [
                'label' => 'Confirmation',
                'trim' => true,
                'required' => true,
                'mapped' => false
            ])
            ->add('site', EntityType::class, [
                'label' => 'Ville de rattachement',
                'trim' => true,
                'required' => true,
                'class' => Sites::class,
                'choice_label'=>'nom',
                'disabled' => true
            ])
            ->add('photo',FileType::class, [
                'label' => 'Ma photo',
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class,[
                'label'=> 'Modifier mon profil',
                'attr' => ['class' => 'customBTN']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
