<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\OneRelation;

use FHPlatform\Component\DoctrineToEs\Provider\DataProvider;
use FHPlatform\Component\DoctrineToEs\Provider\MappingProvider;

class TestCaseOneRelation extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected MappingProvider $mappingProvider;
    protected DataProvider $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var MappingProvider $mappingProvider */
        $mappingProvider = $this->container->get(MappingProvider::class);
        $this->mappingProvider = $mappingProvider;

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->container->get(DataProvider::class);
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
