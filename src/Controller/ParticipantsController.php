<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\ParticipantsType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route("/admin/", name:'app_admin_')]
class ParticipantsController extends AbstractController
{

    #[Route('participants', name:'participants', methods: ['GET', 'POST'])]
    public function participants(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participants = $entityManager->getRepository(Participants::class)->findAll();

        $newParticipant = new Participants();
        $participantsForm = $this->createForm(ParticipantsType::class, $newParticipant);
        $participantsForm = $participantsForm->handleRequest($request);

        //Ajouter un participant
        if ($participantsForm->isSubmitted() && $participantsForm->isValid()) {
            $newParticipant = $participantsForm->getData();
            $entityManager->persist($newParticipant);
            $entityManager->flush();

            //Message flash notifiant l'ajout
            $this->addFlash('success', 'Le participant a été ajouté avec succès !');

            //Redirection vers la même page pour actualiser et ne pas soumettre le formulaire en double
            return $this->redirectToRoute('app_admin_participants');
        }

        return $this->render('admin/participants.html.twig', [
            'participants' => $participants,
            'participantsForm' => $participantsForm->createView(),
        ]);
    }

}