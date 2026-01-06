<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\Item;
use App\Repository\DrawerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent('InlineEditItemDrawer')]
final class InlineEditItemDrawer extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    public function __construct(
        private readonly DrawerRepository $drawerRepository
    )
    {
    }

    #[LiveProp]
    #[Assert\Valid]
    public ?Item $item = null;

    #[LiveProp]
    public bool $isEditing = false;

    #[LiveProp]
    public ?string $flashMessage = null;

    #[LiveProp(writable: true)]
    #[Assert\Positive]
    public ?int $drawerId = null;

    public function mount(): void
    {
        $this->drawerId = $this->item?->getDrawer()?->getId();
    }

    #[LiveAction]
    public function activateEditing(): void
    {
        $this->isEditing = true;
        $this->flashMessage = null;
    }

    #[LiveAction]
    public function save(
        EntityManagerInterface $entityManager,
        DrawerRepository $drawerRepository
    ): void {
        $this->validate();

        if ($this->item) {
            $drawer = $this->drawerId
                ? $drawerRepository->find($this->drawerId)
                : null;

            $this->item->setDrawer($drawer);
            $entityManager->flush();
        }

        $this->isEditing = false;
        $this->flashMessage = 'Saved!';
    }

    public function getDrawerOptions(): array
    {
        $cabinet = $this->item?->getDrawer()?->getCabinet();

        if (!$cabinet) {
            return [];
        }

        return $this->drawerRepository->findEmptyDrawersForCabinet($cabinet);
    }
}
