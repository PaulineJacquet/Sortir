<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\MdpType;
use App\Form\MonProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class MonProfilController extends AbstractController
{

    #[Route('/monprofil', name: 'app_mon_profil', methods: ['GET', 'POST'])]
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
            'formProfil' => $form->createView()
        ]);
    }
    #[Route('/password', name: 'app_password', methods: ['GET', 'POST'])]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $participant = $this->getUser();

        $form = $this->createForm(MdpType::class,$participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($passwordHasher->isPasswordValid($participant, $form->get('currentPassword')->getData())) {
                $plainPassword = $form->get('plainPassword')['first']->getData();
                $newhashedPassword = $passwordHasher->hashPassword($participant, $plainPassword);
                $participant->setPassword($newhashedPassword);
                $entityManager->flush();
                $this->addFlash('success', 'Le mot de passe a été modifié avec succés !');
                return $this->redirectToRoute('app_mon_profil');
            } else{
                $this->addFlash('danger', 'Ancien mot de passe incorrect');
            }
        }

        return $this->render('mon_profil/Password.html.twig', [
           'formMdp' => $form->createView(),
        ]);
    }

}
