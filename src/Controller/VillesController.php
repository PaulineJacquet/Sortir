<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VillesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'app_admin_')]
class VillesController extends AbstractController
{

    #[Route('villes', name: 'villes', methods: ['GET', 'POST'])]
    public function villes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $villeRepository = $entityManager->getRepository(Ville::class);
        $villes = $villeRepository->findAll();

        $villes = new Ville();
        $villesForm = $this->createForm(VillesType::class, $villes);
        $villesForm->handleRequest($request);

        //Ajouter d'une ville
        if ($villesForm->isSubmitted() && $villesForm->isValid()) {
            $ville = $villesForm->getData();
            $entityManager->persist($ville);
            $entityManager->flush();

            //Message flash notifiant l'ajout
            $this->addFlash('success', 'La ville a été ajoutée avec succès !');

            //Redirection vers la même page pour actualiser et ne pas soumettre le formulaire en double
            return $this->redirectToRoute('app_admin_villes');
        }

        // Supprimer une ville
        if ($request->isMethod('POST') && $request->request->has('delete')) {
            $id = $request->request->get('delete');
            $villes = $villeRepository->find($id);

            if (!$villes) {
                throw $this->createNotFoundException('La ville n\'existe pas.');
            }

            $entityManager->remove($villes);
            $entityManager->flush();

            //Message flash notifiant la suppression
            $this->addFlash('success', 'La ville a été supprimée avec succès !');

            //Redirection vers la même page pour actualiser et ne pas soumettre le formulaire en double
            return $this->redirectToRoute('app_admin_villes');
        }

        return $this->render('admin/villes.html.twig', [
            'villes' => $villes,
            'villesForm' => $villesForm->createView()
        ]);
    }

}