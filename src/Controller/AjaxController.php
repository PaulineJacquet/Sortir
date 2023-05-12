<?php

namespace App\Controller;


use App\Entity\Lieu;
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
        $idville=$req->query->get('ville_id');
        $ville=$em->getRepository(Ville::class)->findOneBy(['id'=>$idville]);
        $cp=$ville->getCodePostal();
        return  new JsonResponse($cp);
    }

    #[NoReturn] #[Route('/sortir/public/ajax/rechercheLieux', name: 'app_ajax_lieux', methods: ['GET'])]
    public function RechercherListeLieux(Request $req,EntityManagerInterface $em): Response
    {
        $idville=$req->query->get('ville_id');
        $lieux=$em->getRepository(Lieu::class)->getLieuxByVille($idville);

        $jsonData = array();
        $idx = 0;
        foreach($lieux as $lieu) {
            $temp = array(
                'nom' => $lieu->getNom(),
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' =>$lieu->getLongitude()
            );
            $jsonData[$idx++] = $temp;
        }
        return new JsonResponse($jsonData);

    }

    #[NoReturn] #[Route('/sortir/public/ajax/rechercheInfosLieux', name: 'app_ajax_infos_lieu', methods: ['GET'])]
    public function RechercherinfosLieu(Request $req,EntityManagerInterface $em): Response
    {
        $nomLieu=$req->query->get('lieu_nom');
        $lieu=$em->getRepository(Lieu::class)->findOneBy(['nom'=>$nomLieu]);

        $infos = array(
            'rue' => $lieu->getRue(),
            'latitude' => $lieu->getLatitude(),
            'longitude' =>$lieu->getLongitude()
        );

        return new JsonResponse($infos);
    }
}
