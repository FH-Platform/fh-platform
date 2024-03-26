<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Location
{
    use IdTrait;
    use AllTypesTrait;

    // One-To-Many (back)
    #[ORM\OneToMany(mappedBy: 'location', targetEntity: User::class)]
    private Collection $users;

    // One-To-One
    #[ORM\OneToOne(mappedBy: 'location', targetEntity: ES_LocationMain::class)]
    private ?ES_LocationMain $locationMain = null;

    // Many-To-One
    #[ORM\ManyToOne(targetEntity: ES_LocationGroup::class, inversedBy: 'locations')]
    private ?ES_LocationGroup $locationGroup = null;

    // One-To-Many
    #[ORM\OneToMany(mappedBy: 'location', targetEntity: ES_LocationItem::class)]
    private Collection $locationItems;

    // Many-To-Many
    #[ORM\ManyToMany(targetEntity: ES_LocationMeta::class, mappedBy: 'locations')]
    private Collection $locationMetas;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->locationItems = new ArrayCollection();
        $this->locationMetas = new ArrayCollection();
    }

    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }

    public function getLocationMain(): ?ES_LocationMain
    {
        return $this->locationMain;
    }

    public function setLocationMain(?ES_LocationMain $locationMain): void
    {
        $this->locationMain = $locationMain;
    }

    public function getLocationItems(): Collection
    {
        return $this->locationItems;
    }

    public function setLocationItems(Collection $locationItems): void
    {
        $this->locationItems = $locationItems;
    }

    public function getLocationGroup(): ?ES_LocationGroup
    {
        return $this->locationGroup;
    }

    public function setLocationGroup(?ES_LocationGroup $locationGroup): void
    {
        $this->locationGroup = $locationGroup;
    }

    public function getLocationMetas(): Collection
    {
        return $this->locationMetas;
    }

    public function setLocationMetas(Collection $locationMetas): void
    {
        $this->locationMetas = $locationMetas;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }
}
