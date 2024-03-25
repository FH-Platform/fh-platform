<?php

namespace FHPlatform\Component\Filter\Tests\Util\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name2;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $number;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $number2;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName2(): ?string
    {
        return $this->name2;
    }

    public function setName2(?string $name2): void
    {
        $this->name2 = $name2;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function getNumber2(): ?int
    {
        return $this->number2;
    }

    public function setNumber2(?int $number2): void
    {
        $this->number2 = $number2;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): void
    {
        $this->roles = $roles;
    }
}
