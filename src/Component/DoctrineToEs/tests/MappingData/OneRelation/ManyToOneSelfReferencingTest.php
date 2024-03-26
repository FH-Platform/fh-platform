<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\MappingData\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class ManyToOneSelfReferencingTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $mentor = $this->populateEntity(new User());
        $user = $this->populateEntity(new User());
        $user->setMentor($mentor);
        $this->save([$user]);

        $mapping = $this->mappingProvider->build($index, ['mentor' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'mentor' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTestUser = $this->dataTest;
        $dataTestUser['id'] = 2;

        $data = $this->dataProvider->build($index, $user, ['mentor' => []]);
        $this->assertEquals(array_merge($dataTestUser, [
            'mentor' => $this->dataTest,
        ]), $data);
    }
}