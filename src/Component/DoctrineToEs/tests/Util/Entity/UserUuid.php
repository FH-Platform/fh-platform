<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class UserUuid
{
    use AllTypesTrait;
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    public ?UuidInterface $uuid = null;

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(?UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }
}
