<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DrawerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tblDrawer')]
#[ORM\Entity(repositoryClass: DrawerRepository::class)]
class Drawer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'drawers')]
    #[ORM\JoinColumn(nullable: false)]
    private Cabinet $cabinet;

    #[ORM\Column(length: 255)]
    private string $label;

    #[ORM\Column]
    private int $position;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'drawer', orphanRemoval: true)]
    private Collection $items;

    private function __construct(
        Cabinet $cabinet,
        string $label,
        int $position
    )
    {
        $this->cabinet = $cabinet;
        $this->label = $label;
        $this->position = $position;
        $this->items = new ArrayCollection();
    }

    public static function create(
        Cabinet $cabinet,
        string $label,
        int $position
    ): static
    {
        return new self($cabinet, $label, $position);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCabinet(): Cabinet
    {
        return $this->cabinet;
    }

    public function setCabinet(Cabinet $cabinet): static
    {
        $this->cabinet = $cabinet;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setDrawer($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getDrawer() === $this) {
                $item->setDrawer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->label;
    }
}
