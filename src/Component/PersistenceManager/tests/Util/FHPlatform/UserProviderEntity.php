<?php

namespace FHPlatform\Component\PersistenceManager\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class UserProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function getIndexConfigAdditional(Index $index, array $config): array
    {
        $config['doctrine_to_es'] =
            [
                'testString',
            ];

        return $config;
    }
}
