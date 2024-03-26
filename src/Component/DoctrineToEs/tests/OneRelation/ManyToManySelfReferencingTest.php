<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToManySelfReferencingTest extends TestCaseOneRelation
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $friend = $this->populateEntity(new User());
        $friend2 = $this->populateEntity(new User());

        $user = $this->populateEntity(new User());
        $user->addFriend($friend);
        $user->addFriend($friend2);

        $this->save([$user]);

        $mapping = $this->mappingProvider->provide($index, ['friends' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'friends' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTestFriend = $this->dataTest;
        $dataTestFriend['id'] = 1;
        $dataTestFriend2 = $this->dataTest;
        $dataTestFriend2['id'] = 2;
        $dataTestUser = $this->dataTest;
        $dataTestUser['id'] = 3;

        $data = $this->dataProvider->provide($index, $user, ['friends' => []]);
        $this->assertEquals(array_merge($dataTestUser, [
            'friends' => [
                $dataTestFriend,
                $dataTestFriend2,
            ],
        ]), $data);
    }
}
