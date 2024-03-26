<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Entity;

use Doctrine\ORM\Mapping as ORM;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\AllTypesTrait;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Trait\IdTrait;

#[ORM\Entity]
class UserApiToken
{
    use IdTrait;
    use AllTypesTrait;
}
