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
}
