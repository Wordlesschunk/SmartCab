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
    private ?Drawer $drawer = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $quantity;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDrawer(): ?Drawer
    {
        return $this->drawer;
    }

    public function setDrawer(Drawer $drawer): static
    {
        $this->drawer = $drawer;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
