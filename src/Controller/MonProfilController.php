<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\MonProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonProfilController extends AbstractController
{
    #[Route('/monprofil', name: 'app_mon_profil')]
    public function monprofil(): Response
    {
        $participant = new Participants();
        $form = $this->createForm(MonProfilType::class,$participant);

        return $this->render('mon_profil/MonProfil.html.twig', [
            'formProfil' => $form->createView(),
        ]);
    }
}
