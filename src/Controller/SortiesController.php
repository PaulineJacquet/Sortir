<?php

namespace App\Controller;


use App\Entity\Etats;
use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Sites;
use App\Entity\Sorties;;
use App\Form\FormTypeSortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\equalToIgnoringCase;


#[IsGranted('ROLE_USER')]
class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]
    public function index(): Response
    {
        return $this->render('sorties/MonProfil.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }

    #[Route('/AddSortie', name: 'app_sorties')]
    public function AddSortie(Request $request,EntityManagerInterface $entityManager): Response
    {
        $sortie= new Sorties();

        $organisateur=$this->getUser();
        $sortie->setOrganisateur($organisateur);

        $site=$entityManager->getRepository(Sites::class)->getSiteByParticpant($organisateur->getId());
        $sortie->setSite($site);

        $form= $this->createForm(FormTypeSortiesType::class,$sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $action=$_POST['action'];
            $nomLieu=$_POST['lieu'];

            $req="";
            switch ($action) {
                case "Enregistrer":
                    $req = "Créee";
                    break;
                case "Publier":
                    $req = "Ouverte";
                    break;
                case "Annuler":
                    return $this->redirectToRoute('app_home');
                    break;
            }

            $etat=$entityManager->getRepository(Etats::class)->findOneBy(['libelle' => $req]);
            $lieu=$entityManager->getRepository(Lieu::class)->findOneBy(['nom'=>$nomLieu]);

            $sortie->setEtat($etat);
            $sortie->setLieu($lieu);

           // dd($sortie);
            $entityManager->persist($sortie);

            //Validation de la transaction
            $entityManager->flush();

            //Message de confirmation
            $this->addFlash('success', 'Votre Sortie a été ajoutée avec succés !');

            //Redirection sur la page de détails
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

}
