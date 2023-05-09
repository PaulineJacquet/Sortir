<?php

namespace App\Controller;

use App\Entity\Sites;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'app_admin_')]
class SitesController extends AbstractController
{

    #[Route('sites', name: 'sites')]
    public function sites(EntityManagerInterface $entityManager): Response
    {
        $sitesRepository = $entityManager->getRepository(Sites::class);
        $sites = $sitesRepository->findAllByID();

        return $this->render('admin/sites.html.twig', [
            'sites' => $sites,
        ]);
    }

}