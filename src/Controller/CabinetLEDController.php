<?php

declare(strict_types=1);

namespace App\Controller;

use App\Classes\Client\WLEDClient;
use App\Entity\Cabinet;
use App\Entity\Drawer;
use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class CabinetLEDController extends AbstractController
{
    #[Route('/cab-on/{id}', name: 'cab_on')]
    public function cabOn(Cabinet $cabinet, WLEDClient $client): Response
    {
        $client->powerOn($cabinet->getIpAddress());

        return $this->redirectToRoute('app_item_index');
    }

    #[Route('/cab-off/{id}', name: 'cab_off')]
    public function cabOff(Cabinet $cabinet, WLEDClient $client): Response
    {
        $client->powerOff($cabinet->getIpAddress());

        return $this->redirectToRoute('app_item_index');
    }

    #[Route('/cab-on-single/{id}', name: 'cab_on_single')]
    public function cabOnSingle(Cabinet $cabinet, WLEDClient $client): Response
    {
        $client->powerOnMultiLED($cabinet->getIpAddress(), [15]);

        return $this->redirectToRoute('app_item_index');
    }

    #[Route('/locate-item/{id}', name: 'locate_item')]
    public function locateItem(Item $item, WLEDClient $client): Response
    {
        $client->powerOnMultiLED(
            $item->getDrawer()->getCabinet()->getIpAddress(),
            [$item->getDrawer()->getPreferredLEDPosition()]
        );

        return $this->redirectToRoute('app_item_index');
    }

    #[Route('/power-on-drawer/{id}', name: 'drawer_power_on')]
    public function powerDrawer(Drawer $drawer, WLEDClient $client): Response
    {
        $client->powerOnMultiLED(
            $drawer->getCabinet()->getIpAddress(),
            [$drawer->getPreferredLEDPosition()]
        );

        return $this->redirectToRoute('app_cabinet_show', [
            'id' => $drawer->getCabinet()->getId(),
        ]);
    }

//    #[Route('/on-pos', name: 'app_pos_on')]
//    public function posOn(WLEDClient $client): Response
//    {
//        dd($client->powerOnSingleLED('192.168.1.215', 9));
//
//        return $this->render('index/index.html.twig', [
//            'controller_name' => 'IndexController',
//        ]);
//    }

//    #[Route('/on-pos-multi', name: 'app_pos_on_multi')]
//    public function posOnMulti(WLEDClient $client): Response
//    {
//        dd($client->powerOnMultiLED('192.168.1.215', [4,8,1]));
//
//        return $this->render('index/index.html.twig', [
//            'controller_name' => 'IndexController',
//        ]);
//    }

    #[Route('/test-flash', name: 'test_flash')]
    public function posOnMussslti(WLEDClient $client): Response
    {

        $client->powerOn('192.168.1.215', 10);
        sleep(1);
        $client->powerOff('192.168.1.215', 10);
        sleep(1);
        $client->powerOn('192.168.1.215', 10);
        sleep(1);
        $client->powerOff('192.168.1.215', 10);
        sleep(1);
        $client->powerOff('192.168.1.215', 10);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }


}
