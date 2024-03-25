<?php

namespace FHPlatform\Component\Persistence\Tests\Util\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class UserUuid
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    public ?UuidInterface $uuid = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nameString = '';

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function getNameString(): ?string
    {
        return $this->nameString;
    }

    public function setNameString(?string $nameString): void
    {
        $this->nameString = $nameString;
    }
}
