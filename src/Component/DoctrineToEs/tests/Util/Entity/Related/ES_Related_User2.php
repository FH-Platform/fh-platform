<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Related;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Related_User2
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToOne(targetEntity: ES_Related_User3::class, inversedBy: 'users2')]
    private ?ES_Related_User3 $user3 = null;

    #[ORM\OneToMany(mappedBy: 'user2', targetEntity: ES_Related_User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getUser3(): ?ES_Related_User3
    {
        return $this->user3;
    }

    public function setUser3(?ES_Related_User3 $user3): void
    {
        $this->user3 = $user3;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }
}
