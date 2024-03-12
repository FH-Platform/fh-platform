<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\Service\Namer\IndexNamer;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Company;

class ProviderEntity_Company extends ProviderEntity
{
    public function getConnection(): string
    {
        return 'default2';
    }

    public function getIndexName(string $className): string
    {
        return 'company_test';
    }

    public function getClassName(): string
    {
        return Company::class;
    }

    public function getEntityData(mixed $entity, array $data, array $mapping): array
    {
        $data['entity_data_level_0_company'] = 0;

        return $data;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        $mapping['decorator_index_mapping_level_0_company'] = 0;

        return $mapping;
    }

    public function getIndexSettings(string $className, array $settings): array
    {
        $settings['decorator_index_settings_level_0_company'] = 0;

        return $settings;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'Company';

        return $entitiesRelated;
    }
}
