<?php

namespace App\Es\Config\Provider\EntityRelated;

use App\Entity\User;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderIndex;

class ProviderEntityRelated_Role extends ProviderIndex
{
    public function getClassName(): string
    {
        return User::class;
    }
}
