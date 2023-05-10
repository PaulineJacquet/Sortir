<?php

namespace App\Controller;


use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }

    #[Route('/inscrire/{id}', name: 'app_inscrire', requirements: ['id' => '\d+'])]
    public function inscrire(int $id,EntityManagerInterface $entityManager,SortiesRepository $Srepo): Response
    {
        $sortie=$Srepo->findOneBy(['id'=>$id]);
        $user=$this->getUser();

        //verifiez les conditions d'insertions

        $dateStr=date('Y-m-d');
        $date=new \DateTime($dateStr);

        //il faut que la sortie est étét publé = etat=ouverte
        if($sortie->getEtat()->getId()==2 and $sortie->getDateLimiteInscription()>$date){

            $sortie->addParticipe($user);
            $user->addEstInscrit($sortie);

            $entityManager->persist($sortie);
            $entityManager->persist($user);

            //Validation de la transaction
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/desinscrire/{id}', name: 'app_desinscrire', requirements: ['id' => '\d+'])]
    public function Desinscrire(int $id,Request $request,EntityManagerInterface $entityManager, SortiesRepository $Srepo): Response
    {
        $sortie=$Srepo->findOneBy(['id'=>$id]);

        $user=$this->getUser();

        $dateStr=date('Y-m-d');
        $date=new \DateTime($dateStr);

        if($sortie->getDateLimiteInscription()>$date){
            $sortie->removeParticipe($user);
            $user->removeEstInscrit($sortie);

            $entityManager->persist($sortie);
            $entityManager->persist($user);

            //Validation de la transaction
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
