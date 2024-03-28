<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Decorator;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class MappingDecoratorTest extends TestCaseEs
{
    public function testSomething(): void
    {
        $index = $this->connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->assertEquals(
            [
                'testInteger' => [
                    'type' => 'integer',
                ],
                'setting' => [
                    'properties' => [
                        'testFloat' => [
                            'type' => 'float',
                        ],
                    ],
                    'type' => 'object',
                ],
            ], $index->getMapping());
    }
}
