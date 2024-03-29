<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\OneRelation;

use FHPlatform\Component\DoctrineToEs\Tests\Builder\MappingData\TestCaseMappingData;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class OneToManyTest extends TestCaseMappingData
{
    public function testSomething(): void
    {
        $index = $this->prepareIndex();

        $user = $this->populateEntity(new User());

        $bill = $this->populateEntity(new Bill());
        $bill->setUser($user);
        $bill2 = $this->populateEntity(new Bill());
        $bill2->setUser($user);

        $this->save([$bill, $bill2]);

        $mapping = $this->mappingProvider->build($index, ['bills' => []]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'bills' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $dataTestBill = $this->dataTest;
        $dataTestBill2 = $this->dataTest;
        $dataTestBill2['id'] = 2;

        $data = $this->dataProvider->build($index, $user, ['bills' => []]);
        $this->assertEquals(array_merge($this->dataTest, [
            'bills' => [
                $dataTestBill,
                $dataTestBill2,
            ],
        ]), $data);
    }
}
