<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Related;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Related_User3
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToOne(targetEntity: ES_Related_User::class, inversedBy: 'users3')]
    private ?ES_Related_User $user = null;

    #[ORM\OneToMany(mappedBy: 'user3', targetEntity: ES_Related_User2::class)]
    private Collection $users2;

    public function __construct()
    {
        $this->users2 = new ArrayCollection();
    }

    public function getUser(): ?ES_Related_User
    {
        return $this->user;
    }

    public function setUser(?ES_Related_User $user): void
    {
        $this->user = $user;
    }

    public function getUsers2(): Collection
    {
        return $this->users2;
    }

    public function setUsers2(Collection $users2): void
    {
        $this->users2 = $users2;
    }
}
