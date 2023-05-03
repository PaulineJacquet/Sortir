<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Sorties;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTypeSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionMax')
            ->add('infosSortie')
            ->add('motifAnnulation')
            ->add('photoSortie')

            ->add('lieu',EntityType::class,[
                'label' => 'lieu :',
                'class' => Lieu::class,
                'mapped' =>false,
                'required'=> false,
                'placeholder' => 'Veuillez choisir un lieu',
                'attr' => ['class' => 'form-control'],
            ]);

            /*
            ->add('organisateur',EntityType::class,[
                'label' => 'Organisateur :',
                'class' => Participants::class,
                'mapped' =>false,
            ]);
               */
        ;
           // ->add('organisateur')

            //->add('etat')
           // ->add('site')

        $builder->add('submit', SubmitType::class, [
            'label' => ('Ajouter la sortie'),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
