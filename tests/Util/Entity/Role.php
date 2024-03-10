<?php

namespace FHPlatform\ClientBundle\Tests\Util\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nameString = '';

    public function getId(): ?int
    {
        return $this->id;
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
