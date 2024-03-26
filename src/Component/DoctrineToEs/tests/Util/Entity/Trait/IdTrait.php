<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
