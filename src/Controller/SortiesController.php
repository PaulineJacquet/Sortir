<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Entity\Ville;
use App\Form\FormTypeSortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_')]
    public function index(): Response
    {
        return $this->render('sorties/index.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }

    #[Route('/AddSortie', name: 'app_sorties')]
    public function AddSortie(Request $request,EntityManagerInterface $entityManager): Response
    {
        $sortie= new Sorties();

        $organisateurs= $this->getUser();
        $site=$entityManager->getRepository(Sites::class)->getSiteByParticpant($organisateurs->getId());

      //  dd($site);


        $sortie->setOrganisateur($organisateurs);
        $sortie->setSite($site);

       //dd($sortie);


        $form= $this->createForm(FormTypeSortiesType::class,$sortie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($sortie);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Votre Sortie a été ajoutée avec succés !');

            // Redirection sur la page de détails
            return $this->redirectToRoute('home', [

            ]);
        }

        return $this->render('sorties/AddSortie.html.twig', [
            'sortie'=> $sortie,
            'formSortie' => $form->createView(),

        ]);
    }
}
