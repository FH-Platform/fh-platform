<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $testString = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTestString(): ?string
    {
        return $this->testString;
    }

    public function setTestString(?string $testString): void
    {
        $this->testString = $testString;
    }
}
