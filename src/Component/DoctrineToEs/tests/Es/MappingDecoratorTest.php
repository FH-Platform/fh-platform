<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Es;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Es\MappingDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\UserProviderEntity;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;

class MappingDecoratorTest extends TestCaseEs
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
            MappingDecorator::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->assertEquals(
            [
                "testInteger" => [
                    "type" => "integer",
                ],
                "setting" => [
                    "properties" => [
                        "testFloat" => [
                            "type" => "float",
                        ],
                    ],
                    "type" => "object",
                ]
            ], $index->getMapping());
    }
}
