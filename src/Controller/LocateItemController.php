<?php

namespace App\Controller;

use App\Classes\Client\WLEDClient;
use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LocateItemController extends AbstractController
{
    #[Route('/findme/{id}', name: 'app_findme')]
    public function index(Item $item, WLEDClient $client): Response
    {

        $client->powerOnMultiLED(
            $item->getDrawer()->getCabinet()->getIpAddress(),
            [$item->getDrawer()->getPosition()]
        );

        return $this->render('show/testers.html.twig', [
            'cabinetName' => $item->getDrawer()->getCabinet()->getName(),
            'activeDrawer' => $item->getDrawer()->getLabel(),
            'dataColumns' => $item->getDrawer()->getCabinet()->getColumnCount(),
            'dataRows' => $item->getDrawer()->getCabinet()->getRowCount(),
        ]);
    }
}
