<?php

namespace App\Es\Config\Provider\EntityRelated;

use App\Entity\User;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderEntityRelated;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderIndex;

class ProviderEntityRelated_Role extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return User::class;
    }
}
