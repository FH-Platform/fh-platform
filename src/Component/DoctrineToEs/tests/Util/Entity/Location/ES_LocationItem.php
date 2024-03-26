<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_LocationItem
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToOne(targetEntity: ES_Location::class, inversedBy: 'locationItems')]
    private ?ES_Location $location = null;

    public function getLocation(): ?ES_Location
    {
        return $this->location;
    }

    public function setLocation(?ES_Location $location): void
    {
        $this->location = $location;
    }
}
