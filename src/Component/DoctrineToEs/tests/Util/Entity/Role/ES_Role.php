<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_Role
{
    use IdTrait;
    use AllTypesTrait;

    // Many-To-Many (back)
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'roles')]
    private Collection $users;

    // One-To-One
    #[ORM\OneToOne(mappedBy: 'role', targetEntity: ES_RoleMain::class)]
    private ?ES_RoleMain $roleMain = null;

    // Many-To-One
    #[ORM\ManyToOne(targetEntity: ES_RoleGroup::class, inversedBy: 'roles')]
    private ?ES_RoleGroup $roleGroup = null;

    // One-To-Many
    #[ORM\OneToMany(mappedBy: 'role', targetEntity: ES_RoleItem::class)]
    private Collection $roleItems;

    // Many-To-Many
    #[ORM\ManyToMany(targetEntity: ES_RoleMeta::class, mappedBy: 'roles')]
    private Collection $roleMetas;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->roleItems = new ArrayCollection();
        $this->roleMetas = new ArrayCollection();
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }

    public function getRoleMain(): ?ES_RoleMain
    {
        return $this->roleMain;
    }

    public function setRoleMain(?ES_RoleMain $roleMain): void
    {
        $this->roleMain = $roleMain;
    }

    public function getRoleItems(): Collection
    {
        return $this->roleItems;
    }

    public function setRoleItems(Collection $roleItems): void
    {
        $this->roleItems = $roleItems;
    }

    public function getRoleGroup(): ?ES_RoleGroup
    {
        return $this->roleGroup;
    }

    public function setRoleGroup(?ES_RoleGroup $roleGroup): void
    {
        $this->roleGroup = $roleGroup;
    }

    public function getRoleMetas(): Collection
    {
        return $this->roleMetas;
    }

    public function setRoleMetas(Collection $roleMetas): void
    {
        $this->roleMetas = $roleMetas;
    }
}
