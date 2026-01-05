<?php

declare(strict_types=1);

namespace App\Controller;

use App\Classes\Client\WLEDClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/on', name: 'app_on')]
    public function index(WLEDClient $client): Response
    {
        dd($client->powerOn('192.168.1.215', 10));

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/off', name: 'app_off')]
    public function off(WLEDClient $client): Response
    {
        dd($client->powerOff('192.168.1.215'));

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/on-pos', name: 'app_pos_on')]
    public function posOn(WLEDClient $client): Response
    {
        dd($client->powerOnSingleLED('192.168.1.215', 9));

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/on-pos-multi', name: 'app_pos_on_multi')]
    public function posOnMulti(WLEDClient $client): Response
    {
        dd($client->powerOnMultiLED('192.168.1.215', [4,8,1]));

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

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
