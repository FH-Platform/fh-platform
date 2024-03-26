<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;

class TestCaseOneRelation extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected MappingBuilder $mappingProvider;
    protected DataBuilder $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var MappingBuilder $mappingProvider */
        $mappingProvider = $this->container->get(MappingBuilder::class);
        $this->mappingProvider = $mappingProvider;

        /** @var DataBuilder $dataProvider */
        $dataProvider = $this->container->get(DataBuilder::class);
        $this->dataProvider = $dataProvider;
    }

    protected array $mappingTest = [
        'id' => [
            'type' => 'integer',
        ],
        'testBoolean' => [
            'type' => 'text',
        ],
        'testInteger' => [
            'type' => 'integer',
        ],
        'testBigint' => [
            'type' => 'integer',
        ],
        'testSmallint' => [
            'type' => 'integer',
        ],
        'testFloat' => [
            'type' => 'float',
        ],
        'testDecimal' => [
            'type' => 'float',
        ],
        'testString' => [
            'type' => 'text',
        ],
        'testText' => [
            'type' => 'text',
        ],
        'testDate' => [
            'type' => 'date',
        ],
        'testDatetime' => [
            'type' => 'date',
        ],
    ];

    protected array $dataTest = [
        'id' => 1,
        'testBoolean' => true,
        'testInteger' => 2,
        'testBigint' => 3,
        'testSmallint' => 4,
        'testFloat' => 5.1,
        'testDecimal' => 6.1,
        'testString' => 'test_string',
        'testText' => 'test_text',
        'testDate' => '2024-01-01T00:00:00+00:00',
        'testDatetime' => '2024-01-01T10:10:00+00:00',
    ];

    protected function populateEntity($entity)
    {
        $entity->setTestBoolean(true);
        $entity->setTestInteger(2);
        $entity->setTestBigint(3);
        $entity->setTestSmallint(4);
        $entity->setTestFloat(5.1);
        $entity->setTestDecimal(6.1);
        $entity->setTestString('test_string');
        $entity->setTestText('test_text');
        $entity->setTestDate(new \DateTime('2024-01-01 00:00'));
        $entity->setTestDatetime(new \DateTime('2024-01-01 10:10'));
        $this->save([$entity]);

        return $entity;
    }
}
