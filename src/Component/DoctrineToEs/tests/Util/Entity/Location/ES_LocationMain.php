<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_LocationMain
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToOne(inversedBy: 'locationMain', targetEntity: ES_Location::class)]
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
