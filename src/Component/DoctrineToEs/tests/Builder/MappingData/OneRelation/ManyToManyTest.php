<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToManyTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

        $role = $this->populateEntity(new Role());
        $role2 = $this->populateEntity(new Role());

        $user = $this->populateEntity(new User());
        $user->addRole($role);
        $user->addRole($role2);

        $this->save([$user]);

        $mapping = $this->mappingProvider->build($index, ['roles' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'roles' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTestRole = $this->dataTest;
        $dataTestRole2 = $this->dataTest;
        $dataTestRole2['id'] = 2;

        $data = $this->dataProvider->build($index, $user, ['roles' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'roles' => [
                $dataTestRole,
                $dataTestRole2,
            ],
        ]), $data);
    }
}
