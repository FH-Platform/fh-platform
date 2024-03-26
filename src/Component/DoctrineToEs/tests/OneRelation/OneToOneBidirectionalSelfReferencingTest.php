<?php

namespace FHPlatform\Component\DoctrineToEs\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Provider\DataProvider;
use FHPlatform\Component\DoctrineToEs\Provider\MappingProvider;
use FHPlatform\Component\DoctrineToEs\Tests\TestCase;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneBidirectionalSelfReferencingTest extends TestCase
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
        $mapping = $mappingProvider->provide($index, ['setting' => []]);

        $setting = new Setting();
        $setting->setTestBoolean(true);
        $setting->setTestInteger(2);
        $setting->setTestBigint(3);
        $setting->setTestSmallint(4);
        $setting->setTestFloat(5.1);
        $setting->setTestDecimal(6.1);
        $setting->setTestString('test_string');
        $setting->setTestText('test_text');
        $setting->setTestDate(new \DateTime('2024-01-01 00:00'));
        $setting->setTestDatetime(new \DateTime('2024-01-01 10:10'));
        $this->save([$setting]);

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
        $user->setSetting($setting);
        $this->save([$user]);

        $this->assertEquals(array_merge($this->mappingTest, [
            'setting' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $dataProvider->provide($index, $user, ['setting' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
                'setting' => $this->dataTest,
        ]), $data);
    }
}
