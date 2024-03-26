<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_RoleMeta
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\ManyToMany(targetEntity: ES_Role::class, inversedBy: 'roleMetas')]
    private Collection $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole(ES_Role $role): self
    {
        $this->roles->add($role);

        return $this;
    }
}
