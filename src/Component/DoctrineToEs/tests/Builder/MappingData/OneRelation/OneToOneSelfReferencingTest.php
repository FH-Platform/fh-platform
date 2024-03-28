<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToOneSelfReferencingTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

        $userBestFriend = $this->populateEntity(new User());

        $user = $this->populateEntity(new User());
        $user->setBestFriend($userBestFriend);
        $this->save([$user]);

        $mapping = $this->mappingProvider->build($index, ['bestFriend' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'bestFriend' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTest = $this->dataTest;
        $dataTest['id'] = 2;

        $data = $this->dataProvider->build($index, $user, ['bestFriend' => []]);
        $this->assertEquals(array_merge($dataTest, [
            'bestFriend' => $this->dataTest,
        ]), $data);
    }
}
