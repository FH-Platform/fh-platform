<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Related;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Related_User
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToOne(targetEntity: ES_Related_User2::class, inversedBy: 'users')]
    private ?ES_Related_User2 $user2 = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ES_Related_User3::class)]
    private Collection $users3;

    public function __construct()
    {
        $this->users3 = new ArrayCollection();
    }

    public function getUser2(): ?ES_Related_User2
    {
        return $this->user2;
    }

    public function setUser2(?ES_Related_User2 $user2): void
    {
        $this->user2 = $user2;
    }

    public function getUsers3(): Collection
    {
        return $this->users3;
    }

    public function setUsers3(Collection $users3): void
    {
        $this->users3 = $users3;
    }
}
