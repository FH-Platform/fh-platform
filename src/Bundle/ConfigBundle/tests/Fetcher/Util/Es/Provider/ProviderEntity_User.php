<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;

class ProviderEntity_User extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['entity_data_level_0_user'] = 0;

        return $data;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['decorator_index_mapping_level_0_user'] = [0];

        return $mapping;
    }

    public function getIndexSettings(Index $index, array $settings): array
    {
        $settings['decorator_index_settings_level_0_user'] = [0];

        return $settings;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'User';

        return $entitiesRelated;
    }
}
