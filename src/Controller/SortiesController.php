<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]
    public function index(): Response
    {
        return $this->render('sorties/index.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }

    #[Route('/AddSortie', name: 'app_sorties')]
    public function AddSortie(): Response
    {



        return $this->render('sorties/AddSortie.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
}
