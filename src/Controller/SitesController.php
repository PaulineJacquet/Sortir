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
        $sites = $sitesRepository->findAllByID();

        $site = new Sites();
        $sitesForm = $this->createForm(SitesType::class, $site);
        $sitesForm->handleRequest($request);

        //Ajouter d'une ville
        if ($sitesForm->isSubmitted() && $sitesForm->isValid()) {
            $site = $sitesForm->getData();
            $entityManager->persist($site);
            $entityManager->flush();

            //Message flash notifiant l'ajout
            $this->addFlash('success', 'La ville a été ajoutée avec succès !');

            //Redirection vers la même page pour actualiser et ne pas soumettre le formulaire en double
            return $this->redirectToRoute('app_admin_sites');
        }

        // Supprimer une ville
        if ($request->isMethod('POST') && $request->request->has('delete')) {
            $id = $request->request->get('delete');
            $site = $sitesRepository->find($id);

            if (!$site) {
                throw $this->createNotFoundException('La ville n\'existe pas.');
            }

            $entityManager->remove($site);
            $entityManager->flush();

            //Message flash notifiant la suppression
            $this->addFlash('success', 'La ville a été supprimée avec succès !');

            //Redirection vers la même page pour actualiser et ne pas soumettre le formulaire en double
            return $this->redirectToRoute('app_admin_sites');
        }

        return $this->render('admin/sites.html.twig', [
            'sites' => $sites,
            'sitesForm' => $sitesForm->createView()
        ]);
    }

}