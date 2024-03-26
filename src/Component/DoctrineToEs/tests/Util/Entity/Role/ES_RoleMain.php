<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class ES_RoleMain
{
    use IdTrait;
    use AllTypesTrait;

    #[ORM\OneToOne(inversedBy: 'roleMain', targetEntity: ES_Role::class)]
    private ?ES_Role $role = null;

    public function getRole(): ?ES_Role
    {
        return $this->role;
    }

    public function setRole(?ES_Role $role): void
    {
        $this->role = $role;
    }
}
