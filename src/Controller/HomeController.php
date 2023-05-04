<?php

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\SortiesFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name:'app_')]
class HomeController extends AbstractController
{

    #[Route('', name:"home", methods: ['GET', 'POST'])]
    public function home(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortiesRepository = $entityManager->getRepository(Sorties::class);

        $filtresForm = $this->createForm(SortiesFilterType::class);
        $filtresForm->handleRequest($request);

        if ($filtresForm->isSubmitted() && $filtresForm->isValid()) {
            $filtres = $filtresForm->getData();
            $sorties = $sortiesRepository->findByFiltres($filtres);
        }

        return $this->render('home/home.html.twig', [
            'sorties' => $sorties,
            'filtresForm' => $filtresForm->createView(),
        ]);
    }

}