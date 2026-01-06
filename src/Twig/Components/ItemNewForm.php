<?php

namespace App\Twig\Components;

use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ItemNewForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): \Symfony\Component\Form\Flow\FormFlowInterface|\Symfony\Component\Form\FormInterface
    {
        return $this->createForm(ItemType::class);
    }}
