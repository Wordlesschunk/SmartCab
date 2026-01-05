<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cabinet;
use App\Entity\Drawer;
use App\Form\CabinetType;
use App\Repository\CabinetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cabinet')]
final class CabinetController extends AbstractController
{
    #[Route(name: 'app_cabinet_index', methods: ['GET'])]
    public function index(CabinetRepository $cabinetRepository): Response
    {
        return $this->render('cabinet/index.html.twig', [
            'cabinets' => $cabinetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cabinet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cabinet = new Cabinet();
        $form = $this->createForm(CabinetType::class, $cabinet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            for ($i = 0; $i < $cabinet->getDrawerCount(); $i++) {
                $drawer = Drawer::create(
                    $cabinet,
                    'Drawer '.$i,
                    $i
                );

                $entityManager->persist($drawer);
                $cabinet->addDrawer($drawer);
            }

            $entityManager->persist($cabinet);
            $entityManager->flush();

            return $this->redirectToRoute('app_cabinet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cabinet/new.html.twig', [
            'cabinet' => $cabinet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cabinet_show', methods: ['GET'])]
    public function show(Cabinet $cabinet): Response
    {
        return $this->render('cabinet/show.html.twig', [
            'cabinet' => $cabinet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cabinet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cabinet $cabinet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CabinetType::class, $cabinet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cabinet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cabinet/edit.html.twig', [
            'cabinet' => $cabinet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cabinet_delete', methods: ['POST'])]
    public function delete(Request $request, Cabinet $cabinet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cabinet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cabinet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cabinet_index', [], Response::HTTP_SEE_OTHER);
    }
}
