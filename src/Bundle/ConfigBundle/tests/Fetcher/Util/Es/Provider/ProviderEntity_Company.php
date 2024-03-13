<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Company;

class ProviderEntity_Company extends ProviderEntity
{
    public function getClassName(): string
    {
        return Company::class;
    }

    public function getAdditionalConfig(): array
    {
        return ['test3' => 'test3'];
    }

    public function getConnection(): string
    {
        return 'default2';
    }

    public function getIndexName(string $className): string
    {
        return 'company_test';
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        $mapping['decorator_index_mapping_level_0_company'] = [0];

        return $mapping;
    }

    public function getIndexSettings(Index $index, array $settings): array
    {
        $settings['decorator_index_settings_level_0_company'] = [0];

        return $settings;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['entity_data_level_0_company'] = 0;

        return $data;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'Company';

        return $entitiesRelated;
    }

    public function getEntityShouldBeIndexed(Index $index, $entity, bool $shouldBeIndexed): bool
    {
        return false;
    }
}
