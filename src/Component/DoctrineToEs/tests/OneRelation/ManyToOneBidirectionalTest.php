<?php

namespace FHPlatform\Component\DoctrineToEs\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Provider\DataProvider;
use FHPlatform\Component\DoctrineToEs\Provider\MappingProvider;
use FHPlatform\Component\DoctrineToEs\Tests\TestCase;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToOneBidirectionalTest extends TestCase
{
    private array $mappingTest = [
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

    private array $dataTest = [
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

    public function testSomething(): void
    {
        /** @var MappingProvider $mappingProvider */
        $mappingProvider = $this->container->get(MappingProvider::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->container->get(DataProvider::class);

        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);
        $mapping = $mappingProvider->provide($index, ['location' => []]);

        $location = new Location();
        $location->setTestBoolean(true);
        $location->setTestInteger(2);
        $location->setTestBigint(3);
        $location->setTestSmallint(4);
        $location->setTestFloat(5.1);
        $location->setTestDecimal(6.1);
        $location->setTestString('test_string');
        $location->setTestText('test_text');
        $location->setTestDate(new \DateTime('2024-01-01 00:00'));
        $location->setTestDatetime(new \DateTime('2024-01-01 10:10'));
        $this->save([$location]);

        $user = new User();
        $user->setTestBoolean(true);
        $user->setTestInteger(2);
        $user->setTestBigint(3);
        $user->setTestSmallint(4);
        $user->setTestFloat(5.1);
        $user->setTestDecimal(6.1);
        $user->setTestString('test_string');
        $user->setTestText('test_text');
        $user->setTestDate(new \DateTime('2024-01-01 00:00'));
        $user->setTestDatetime(new \DateTime('2024-01-01 10:10'));
        $user->setLocation($location);
        $this->save([$user]);

        $this->assertEquals(array_merge($this->mappingTest, [
            'location' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $dataProvider->provide($index, $user, ['location' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
                'location' => $this->dataTest,
        ]), $data);
    }
}
