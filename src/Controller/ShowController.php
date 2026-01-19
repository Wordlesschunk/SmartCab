<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowController extends AbstractController
{
    #[Route('/show', name: 'app_show')]
    public function index(): Response
    {
        return $this->render('show/testers.html.twig', [
            'maxDrawers' => 17,
            'dataColumns' => 5,
            'dataRows' => 10,
        ]);


//        return $this->render('show/index.html.twig', [
//            'controller_name' => 'ShowController',
//        ]);
    }
}
