<?php

namespace App\Controller;


use App\Entity\Etats;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Entity\Ville;
use App\Form\FormTypeSortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]
    public function index(): Response
    {
        return $this->render('sorties/MonProfil.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/AddSortie', name: 'app_sorties')]
    public function AddSortie(Request $request,EntityManagerInterface $entityManager): Response
    {
        $sortie= new Sorties();
        //$lieu = new Lieu();
        $organisateurs= $entityManager->getRepository(Participants::class)->findAll();
        $lieu=$entityManager->getRepository(Lieu::class)->findAll();

        $organisateurs= $this->getUser();
        $site=$entityManager->getRepository(Sites::class)->getSiteByParticpant($organisateurs->getId());
        $etat=new Etats();


        $sortie->setOrganisateur($organisateurs);
        $sortie->setSite($site);
        $sortie->setEtat($etat);


        $form= $this->createForm(FormTypeSortiesType::class,$sortie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($sortie);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Votre Sortie a été ajoutée avec succés !');

            // Redirection sur la page de détails
            return $this->redirectToRoute('app_home', [

            ]);
        }

        return $this->render('sorties/AddSortie.html.twig', [
            'sortie'=> $sortie,
            'formSortie' => $form->createView(),

        ]);
    }

    #[Route('/sortie/{id}', name: 'app_details', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function details(int $id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->getRepository(Sorties::class)->findOneBy(['id' => $id]);

        return $this->render('sorties/details.html.twig', [
            'sortie'=> $sortie,
        ]);
    }


    #[Route('/update_cp', name: 'update_cp', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function updateCP(Request $request,int $id, EntityManagerInterface $entityManager)
    {
        $ville=$entityManager->getRepository(Ville::class)->findOneBy(['id'=>$id]);
        $cp=$ville->getCodePostal();

        // Récupérez la liste des villes pour le pays sélectionné
        // ...dd
        dd($cp);
        // Convertissez la liste des villes en format JSON et renvoyez-la
        return new JsonResponse($cp);
    }



}
