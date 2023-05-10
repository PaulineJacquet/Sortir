<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/', name: 'app_admin_')]
class VillesController extends AbstractController
{

    #[Route('villes', name: 'villes', methods: ['GET', 'POST'])]
    public function villes(Request $request, EntityManagerInterface $entityManager): Response
    {

    }

}