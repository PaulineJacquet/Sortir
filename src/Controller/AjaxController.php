<?php

namespace App\Controller;


use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    #[Route('/sortir/public/ajax/', name: 'app_ajax', methods: ['GET'])]
    public function index(Request $req): Response
    {
        return 1;
    }

    #[NoReturn] #[Route('/sortir/public/ajax/rechercheCodePostal', name: 'app_ajax_cp', methods: ['GET'])]
    public function RechercherCP(Request $req,EntityManagerInterface $em): Response
    {
        $Idville=$req->query->get('ville_id');
        $ville=$em->getRepository(Ville::class)->findOneBy(['id'=>$Idville]);
        $cp=$ville->getCodePostal();

        return  new JsonResponse($cp);
    }
}
