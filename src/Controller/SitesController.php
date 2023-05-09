<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Form\SitesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'app_admin_')]
class SitesController extends AbstractController
{

    #[Route('sites', name: 'sites', methods: ['GET', 'POST'])]
    public function sites(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sitesRepository = $entityManager->getRepository(Sites::class);
        $sites= $sitesRepository->findAllByID();

        $site = new Sites();
        $sitesForm = $this->createForm(SitesType::class, $site);
        $sitesForm->handleRequest($request);

        //Ajout d'une ville
        if ($sitesForm->isSubmitted() && $sitesForm->isValid()) {
            $site = $sitesForm->getData();
            $entityManager->persist($site);
            $entityManager->flush();

            //Message flash notifiant l'ajout
            $this->addFlash('success', 'La ville a été ajoutée avec succès !');

            //Redirection vers la même page pour actualiser et éviter le double ajout
            return $this->redirectToRoute('app_admin_sites');
        }

        return $this->render('admin/sites.html.twig', [
            'sites' => $sites,
            'sitesForm' => $sitesForm->createView()
        ]);
    }

}