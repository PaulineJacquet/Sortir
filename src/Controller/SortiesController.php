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
        //instancie une nouvelle sortie
        $sortie= new Sorties();

        //on lui ajoute l'organisateur à savoir l'utilisateur actuellement connecté
        $organisateur=$this->getUser();
        $sortie->setOrganisateur($organisateur);

        //on lui ajoute le site de rattachement
        $idSite=$organisateur->getSite();
        $site=$entityManager->getRepository(Sites::class)->findOneBy(['id'=>$idSite]);
        $sortie->setSite($site);

        //création du formulaire
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

            //ajout de l'etat
            $sortie->setEtat($etat);
            $sortie->setLieu($lieu);

            $entityManager->persist($sortie);

            //Validation de la transaction
            $entityManager->flush();

            //Message de confirmation
            $this->addFlash('success', 'Votre sortie a été ajoutée avec succès !');

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

        //Récup les participants en fonction de la sortie
        $participants = $sortie->getParticipe();

        return $this->render('sorties/details.html.twig', [
            'sortie'=> $sortie,
            'participants' => $participants,
        ]);
    }

    #[Route('/annuler/{id}', name: 'app_annuler_sortie', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function annuler(int $id, EntityManagerInterface $entityManager,Request $request): Response
    {
        $sortie = $entityManager->getRepository(Sorties::class)->findOneBy(['id' => $id]);
        $motif = $request->request->filter('motif',null,FILTER_SANITIZE_STRING);

        if(!empty($motif)){
            $etat = $entityManager->getRepository(Etats::class)->findOneBy(['id' => 6]);

            $sortie->setMotifAnnulation($motif);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a été annulée !');
        }
        return $this->render('sorties/annuler.html.twig', [
            'sortie'=> $sortie,
        ]);
    }
    #[Route('/publier/{id}', name: 'app_publier', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function publier(int $id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->getRepository(Sorties::class)->findOneBy(['id' => $id]);
        $etat = $entityManager->getRepository(Etats::class)->findOneBy(['id' => 2]);
        $sortie->setEtat($etat);
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('success', 'La sortie est publiée !');

        return $this->redirectToRoute('app_home');
    }
    #[Route('/modifier/{id}', name: 'app_modifier_sortie', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function modifier(int $id, EntityManagerInterface $entityManager,Request $request): Response
    {
        $sortie = $entityManager->getRepository(Sorties::class)->findOneBy(['id' => $id]);
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
                case "Supprimer":
                    $entityManager->remove($sortie);
                    $entityManager->flush();
                    $this->addFlash('success', 'La sortie a été supprimée !');
                    return $this->redirectToRoute('app_home');
                    break;
                case "Annuler":
                    return $this->redirectToRoute('app_home');
                    break;
            }

            $etat = $entityManager->getRepository(Etats::class)->findOneBy(['libelle' => $req]);
            $lieu = $entityManager->getRepository(Lieu::class)->findOneBy(['nom'=>$nomLieu]);

            $sortie->setEtat($etat);
            $sortie->setLieu($lieu);

            // dd($sortie);
            $entityManager->persist($sortie);

            //Validation de la transaction
            $entityManager->flush();

            //Message de confirmation
            $this->addFlash('success', 'Votre sortie a été modifiée !');

            //Redirection sur la page de détails
            return $this->redirectToRoute('app_home', [

            ]);
        }
        return $this->render('sorties/modifier.html.twig', [
            'sortie'=> $sortie,
            'formSortie' => $form->createView(),

        ]);
    }

}
