<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sorties;
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
            if($form->get('photo')->getData()!=null){
                $fileName=$form->get('photo')->getData();
                //encoder la photo
                $fileNameEncrypted=md5(uniqid()).'.'.$fileName->guessExtension();

                //déplacer le fichierencrypté dans le dossier des images
                $fileName->move($this->getParameter('user_photo_directory'),$fileNameEncrypted);

                //hydrater la photo dans l'objet participant
                $participant->setPhoto($fileNameEncrypted);

            }
            $entityManager->persist($participant);

            $entityManager->flush();
            $this->addFlash('success', 'Le profil a été modifié avec succès !');
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
                $this->addFlash('success', 'Le mot de passe a été modifié avec succès !');
                return $this->redirectToRoute('app_mon_profil');
            } else{
                $this->addFlash('danger', 'Mot de passe incorrect');
            }
        }

        return $this->render('mon_profil/Password.html.twig', [
           'formMdp' => $form->createView(),
        ]);
    }
    #[Route('/profil', name: 'app_profil', methods: ['GET','POST'])]
    public function profil(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $id = $request->request->get('id');
            $profil = $entityManager->getRepository(Participants::class)->findOneBy(['id' => $id]);
            return $this->render('mon_profil/Profil.html.twig', [
                'profil' => $profil
            ]);
        }else{
            $this->addFlash('warning', 'Accès non autorisé');
            return $this->redirectToRoute('app_home');
        }
    }
}
