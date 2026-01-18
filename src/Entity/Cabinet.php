<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CabinetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tblCabinet')]
#[ORM\Entity(repositoryClass: CabinetRepository::class)]
class Cabinet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $ipAddress;

    #[ORM\Column]
    private int $drawerCount;

    /**
     * @var Collection<int, Drawer>
     */
    #[ORM\OneToMany(targetEntity: Drawer::class, mappedBy: 'cabinet', orphanRemoval: true)]
    private Collection $drawers;

    public function __construct()
    {
        $this->drawers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getDrawerCount(): int
    {
        return $this->drawerCount;
    }

    public function setDrawerCount(int $drawerCount): static
    {
        $this->drawerCount = $drawerCount;

        return $this;
    }

    /**
     * @return Collection<int, Drawer>
     */
    public function getDrawers(): Collection
    {
        return $this->drawers;
    }

    public function addDrawer(Drawer $drawer): static
    {
        if (!$this->drawers->contains($drawer)) {
            $this->drawers->add($drawer);
            $drawer->setCabinet($this);
        }

        return $this;
    }

    public function removeDrawer(Drawer $drawer): static
    {
        if ($this->drawers->removeElement($drawer)) {
            // set the owning side to null (unless already changed)
            if ($drawer->getCabinet() === $this) {
                $drawer->setCabinet(null);
            }
        }

        return $this;
    }
}
