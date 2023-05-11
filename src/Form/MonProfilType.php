<?php

namespace App\Form;

use App\Entity\Participants;
use App\Entity\Sites;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;

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
                'mapped'=>false,
                'required' => false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'1024k',

                        //restreindre sur le type de fichier qui peut être uploader
                    ])
                ],
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
