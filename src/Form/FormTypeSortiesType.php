<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FormTypeSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nom',TextType::class,[
        'label'=>'Nom de la sortie   ',
            'required'=>true
        ]);

        $builder->add('dateHeureDebut',DateTimeType::class,[
            'label'=>'Debut de la sortie ',
            'required'=>true,
            'html5' => true,
            'widget' => 'single_text',
           'attr' => ['class' => 'datepicker'],
        ]);

        $builder->add('duree',IntegerType::class,[
            'label'=>'Durée de la sortie  ',
            'attr'=>['placeholder'=>' la durée de votre sortie en heures'],


        ]);

        $builder->add('dateLimiteInscription',DateTimeType::class,[
            'label'=>'Date limite inscription  ',
            'required'=>true,
            'html5' => true,
            'widget' => 'single_text',
            'attr' => ['class' => 'js-datepicker'],
        ]);

        $builder->add('nbInscriptionMax',IntegerType::class,[
            'label'=>'Nb de places   ',
            'required'=>true,


        ]);

        $builder->add('infosSortie',TextareaType::class,[
            'label'=>'Infos sortie   '
        ]);

        /*
        $builder ->add('lieu',EntityType::class,[
            'label' => 'lieu :',
            'class' => Lieu::class,
            'choice_label'=>'nom',
            'attr' => ['class' => 'form-control'],
            'required'=>true
        ]);
    */
        /*
        $builder ->add('organisateur',EntityType::class , [
            'label' => 'Organisateur :',
            'class' => Participants::class,
            'choice_label'=>'pseudo',
            'required'=>true
        ]);
         */

        $builder ->add('ville',EntityType::class , [
            'label' => 'Ville :',
            'class' => Ville::class,
            'choice_label'=>'nom',
            'mapped' =>false,
            'required'=>false,
            'placeholder' => 'Selectionner une ville',
        ]);



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
