<?php

namespace App\Controller;

use App\Entity\Inscriptions;
use App\Entity\Sorties;
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
    public function inscrire(int $id,Request $request,EntityManagerInterface $entityManager): Response
    {
        $inscription=new Inscriptions();

        $sortie=$entityManager->getRepository(Sorties::class)->findOneBy(['id'=>$id]);

        $inscription->setSortie($sortie);//id le la sortie sur laquelle on clique
        $inscription->setParticipant($this->getUser());

        $dateStr=date('Y-m-d');
        $date=new \DateTime($dateStr);
        //dd($date);

        $inscription->setDateInscription($date);

        $entityManager->persist($inscription);

        //Validation de la transaction
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/desinscrire/{id}', name: 'app_desinscrire', requirements: ['id' => '\d+'])]
    public function Desinscrire(int $id,Request $request,EntityManagerInterface $entityManager): Response
    {
        $sortie=$entityManager->getRepository(Sorties::class)->findOneBy(['id'=>$id]);

        $user=$this->getUser();

        $inscription=$entityManager->getRepository(Inscriptions::class)->findOneBySortieAndParticipant($sortie->getId(),$user->getId());
        //dd($inscription);

        $entityManager->remove($inscription);

        //Validation de la transaction
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
