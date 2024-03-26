<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneTest extends TestCaseOneRelation
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $userBestFriend = $this->populateEntity(new User());

        $user = $this->populateEntity(new User());
        $user->setBestFriend($userBestFriend);
        $this->save([$user]);

        $mapping = $this->mappingProvider->provide($index, ['bestFriend' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'bestFriend' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTest = $this->dataTest;
        $dataTest['id'] = 2;

        $data = $this->dataProvider->provide($index, $user, ['bestFriend' => []]);
        $this->assertEquals(array_merge($dataTest, [
            'bestFriend' => $this->dataTest,
        ]), $data);
    }
}
