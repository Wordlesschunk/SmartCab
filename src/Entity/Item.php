<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tblItem')]
#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private Drawer $drawer;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $quantity;
}
