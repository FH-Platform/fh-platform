<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToManySelfReferencingTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

        $user = $this->populateEntity(new User());

        $student = $this->populateEntity(new User());
        $student->setMentor($user);
        $student2 = $this->populateEntity(new User());
        $student2->setMentor($user);

        $this->save([$student, $student2]);

        $mapping = $this->mappingProvider->build($index, ['students' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'students' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTestStudent = $this->dataTest;
        $dataTestStudent['id'] = 2;
        $dataTestStudent2 = $this->dataTest;
        $dataTestStudent2['id'] = 3;

        $data = $this->dataProvider->build($index, $user, ['students' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'students' => [
                $dataTestStudent,
                $dataTestStudent2,
            ],
        ]), $data);
    }
}
