<?php

namespace FHPlatform\Component\DoctrineToEs\Basic;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\TestCase;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var MappingBuilder $mappingProvider */
        $mappingProvider = $this->container->get(MappingBuilder::class);

        /** @var DataBuilder $dataProvider */
        $dataProvider = $this->container->get(DataBuilder::class);

        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);
        $mapping = $mappingProvider->provide($index, []);

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
        $this->save([$user]);

        $this->assertEquals([
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
        ], $mapping);

        $data = $dataProvider->provide($index, $user, []);
        $this->assertEquals(
            [
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
            ], $data);
    }
}
