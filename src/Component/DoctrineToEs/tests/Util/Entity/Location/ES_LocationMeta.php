<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_LocationMeta
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToMany(targetEntity: ES_Location::class, inversedBy: 'locationMetas')]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function setLocations(Collection $locations): void
    {
        $this->locations = $locations;
    }

    public function addLocation(ES_Location $location): self
    {
        $this->locations->add($location);

        return $this;
    }
}
