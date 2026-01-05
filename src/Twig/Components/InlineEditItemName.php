<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class InlineEditItemName extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['name'])]
    #[Assert\Valid]
    public Item $item;

    #[LiveProp]
    public bool $isEditing = false;

    public ?string $flashMessage = null;

    #[LiveAction]
    public function activateEditing()
    {
        $this->isEditing = true;
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // if validation fails, this throws an exception & the component re-renders
        $this->validate();

        $this->isEditing = false;
        $this->flashMessage = 'Saved! Well, not actually because this is a demo (if you refresh, the value will go back).';

         $entityManager->flush();
    }
}
