<?php

namespace App\Es\Config\Provider\Entity;

use App\Entity\User;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderIndex;

class ProviderEntity_User extends ProviderIndex
{
    public function getClassName(): string
    {
        return User::class;
    }
}
