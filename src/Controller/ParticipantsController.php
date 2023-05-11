<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sites;
use App\Form\ParticipantsType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route("/admin/", name:'app_admin_')]
class ParticipantsController extends AbstractController
{

    #[Route('participants', name:'participants', methods: ['GET', 'POST'])]
    public function participants(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $participants = $entityManager->getRepository(Participants::class)->findAll();
        $siteRepository = $entityManager->getRepository(Sites::class);
        $sites = $siteRepository->findAll();

        $newParticipant = new Participants();
        $participantsForm = $this->createForm(ParticipantsType::class, $newParticipant, [
            'sites' => $sites,
        ]);
        $participantsForm = $participantsForm->handleRequest($request);

        //Ajouter un participant
        if ($participantsForm->isSubmitted() && $participantsForm->isValid()) {
            $newParticipant = $participantsForm->getData();

            // Hasher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($newParticipant, $newParticipant->getPassword());
            $newParticipant->setPassword($hashedPassword);

            $entityManager->persist($newParticipant);
            $entityManager->flush();

            //Message flash notifiant l'ajout
            $this->addFlash('success', 'Le participant a été ajouté avec succès !');

            //Redirection vers la même page pour actualiser et ne pas soumettre le formulaire en double
            return $this->redirectToRoute('app_admin_participants');
        }

        // Suppression d'un participant
        if ($request->isMethod('POST') && $request->request->has('delete')) {
            $participantId = $request->request->get('delete');
            $participant = $entityManager->getRepository(Participants::class)->find($participantId);

            if ($participant) {
                $entityManager->remove($participant);
                $entityManager->flush();

                // Message flash notifiant la suppression
                $this->addFlash('success', 'Le participant a été supprimé avec succès !');
            }

            // Redirection vers la même page pour actualiser la liste des participants
            return $this->redirectToRoute('app_admin_participants');
        }

        return $this->render('admin/participants.html.twig', [
            'participants' => $participants,
            'participantsForm' => $participantsForm->createView(),
        ]);
    }

}