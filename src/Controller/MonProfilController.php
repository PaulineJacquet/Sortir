<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\MonProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[IsGranted('ROLE_USER')]
class MonProfilController extends AbstractController
{

    #[Route('/monprofil', name: 'app_mon_profil')]
    public function monprofil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participant = $this->getUser();

        $form = $this->createForm(MonProfilType::class,$participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le profil a été modifié avec succés !');
            return $this->redirectToRoute('app_mon_profil');

        }

        return $this->render('mon_profil/MonProfil.html.twig', [
            'formProfil' => $form->createView(),
        ]);
    }
}
