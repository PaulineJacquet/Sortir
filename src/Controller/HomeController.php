<?php

namespace App\Controller;

use App\Entity\Inscriptions;
use App\Entity\Sorties;
use App\Form\SortiesFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[Route(name:'app_')]
class HomeController extends AbstractController
{
    #[Route('home', name:"home", methods: ['GET', 'POST'])]
    public function home(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortiesRepository = $entityManager->getRepository(Sorties::class);
        $sorties = $sortiesRepository->findAllByDateHeureDebut();

        $filtresForm = $this->createForm(SortiesFilterType::class);
        $filtresForm->handleRequest($request);

        $dateStr=date('Y-m-d');
        $date=new \DateTime($dateStr);

        if ($filtresForm->isSubmitted() && $filtresForm->isValid()) {
            $filtres = $filtresForm->getData();
            $sorties = $sortiesRepository->findByFiltres($filtres);
        }

        return $this->render('home/home.html.twig', [
            'sorties' => $sorties,
            'ADJ'=>$date,
            'filtresForm' => $filtresForm->createView(),
        ]);
    }

}