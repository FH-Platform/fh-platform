<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\EntitiesRelated;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class Basic3Test extends TestCaseEntitiesRelated
{
    public function testSomething(): void
    {
        $data = [
            'default' => [
                Role::class => [
                    User::class => [
                        'relations' => 'users',
                        'changed_fields' => [
                            'testBoolean',
                        ],
                    ],
                ],
            ],
        ];

        $role = new Role();
        $role2 = new Role();
        $this->save([$role, $role2]);

        $user = new User();
        $user->setRoles(new ArrayCollection([$role, $role2]));

        $user2 = new User();
        $user2->setRoles(new ArrayCollection([$role]));

        $this->save([$user, $user2]);

        $this->assertEquals([], $this->entitiesRelatedBuilder->build($role, $data, ['testString']));
        $this->assertEquals([
            'users' => [
                1 => $user,
                2 => $user2,
            ],
        ], $this->entitiesRelatedBuilder->build($role, $data, ['testBoolean']));

        $this->assertEquals([
            'users' => [
                1 => $user,
            ],
        ], $this->entitiesRelatedBuilder->build($role2, $data, ['testBoolean']));
    }
}
