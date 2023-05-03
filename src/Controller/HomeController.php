<?php

namespace App\Controller;

use App\Entity\Sorties;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name:'home_')]
class HomeController extends AbstractController
{

    #[Route('', name:"home", methods: ['GET'])]
    public function home(EntityManagerInterface $entityManager): Response
    {
        $sorties = $entityManager->getRepository(Sorties::class)->findAll();

        return $this->render('home/home.html.twig', [
            'sorties' => $sorties,
        ]);
    }

}