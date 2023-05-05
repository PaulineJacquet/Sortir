<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\MonProfilType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonProfilController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/monprofil', name: 'app_mon_profil')]
    public function monprofil(): Response
    {
        $participant = $this->getUser();
        $form = $this->createForm(MonProfilType::class,$participant);

        return $this->render('mon_profil/MonProfil.html.twig', [
            'formProfil' => $form->createView(),
        ]);
    }
}
