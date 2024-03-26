<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Provider\DataProvider;
use FHPlatform\Component\DoctrineToEs\Provider\MappingProvider;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneBidirectionalTest extends TestCaseOneRelation
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
        $mapping = $mappingProvider->provide($index, ['bestFriend' => []]);

        $userBestFriend = new User();
        $userBestFriend->setTestBoolean(true);
        $userBestFriend->setTestInteger(2);
        $userBestFriend->setTestBigint(3);
        $userBestFriend->setTestSmallint(4);
        $userBestFriend->setTestFloat(5.1);
        $userBestFriend->setTestDecimal(6.1);
        $userBestFriend->setTestString('test_string');
        $userBestFriend->setTestText('test_text');
        $userBestFriend->setTestDate(new \DateTime('2024-01-01 00:00'));
        $userBestFriend->setTestDatetime(new \DateTime('2024-01-01 10:10'));
        $this->save([$userBestFriend]);

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
        $user->setBestFriend($userBestFriend);
        $this->save([$user]);

        $this->assertEquals(array_merge($this->mappingTest, [
            'bestFriend' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTest = $this->dataTest;
        $dataTest['id'] = 2;

        $data = $dataProvider->provide($index, $user, ['bestFriend' => []]);
        $this->assertEquals(array_merge($dataTest, [
            'bestFriend' => $this->dataTest,
        ]), $data);
    }
}
