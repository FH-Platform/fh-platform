<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\OneRelation;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Bill\Bill;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Location\Location;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class AllTest extends TestCaseOneRelation
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $user = $this->populateEntity(new User());

        //one-to-one
        $setting = $this->populateEntity(new Setting());
        $user->setSetting($setting);
        $this->save([$user]);

        //one-to-one self-referencing
        $userBestFriend = $this->populateEntity(new User());
        $user->setBestFriend($userBestFriend);
        $this->save([$user]);

        //many-to-one
        $location = $this->populateEntity(new Location());
        $user->setLocation($location);
        $this->save([$user]);

        //many-to-one self-referencing
        $mentor = $this->populateEntity(new User());
        $user->setMentor($mentor);
        $this->save([$user]);

        //one-to-many
        $bill = $this->populateEntity(new Bill());
        $bill->setUser($user);
        $bill2 = $this->populateEntity(new Bill());
        $bill2->setUser($user);
        $this->save([$bill, $bill2]);

        //one-to-many self-referencing
        $student = $this->populateEntity(new User());
        $student->setMentor($user);
        $student2 = $this->populateEntity(new User());
        $student2->setMentor($user);
        $this->save([$student, $student2]);

        //many-to-many
        $role = $this->populateEntity(new Role());
        $role2 = $this->populateEntity(new Role());
        $user->addRole($role);
        $user->addRole($role2);
        $this->save([$user]);

        //many-to-many self-referencing
        $friend = $this->populateEntity(new User());
        $friend2 = $this->populateEntity(new User());
        $user->addFriend($friend);
        $user->addFriend($friend2);
        $this->save([$user]);

        $mapping = $this->mappingProvider->provide($index, [
            'setting' => [],
            'bestFriend' => [],
            'location' => [],
            'mentor' => [],
            'bills' => [],
            'students' => [],
            'roles' => [],
            'friends' => [],
        ]);
        $this->assertEquals(array_merge($this->mappingTest, [
            'setting' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
            'bestFriend' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
            'location' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
            'mentor' => [
                'type' => 'object',
                'properties' => $this->mappingTest,
            ],
            'bills' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
            'students' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
            'roles' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
            'friends' => [
                'type' => 'nested',
                'properties' => $this->mappingTest,
            ],
        ]), $mapping);

        $data = $this->dataProvider->provide($index, $user, [
            'setting' => [],
            'bestFriend' => [],
            'location' => [],
            'mentor' => [],
            'bills' => [],
            'students' => [],
            'roles' => [],
            'friends' => [],
        ]);

        $dataTestBestFriend = $this->dataTest;
        $dataTestBestFriend['id'] = 2;

        $dataTestMentor = $this->dataTest;
        $dataTestMentor['id'] = 3;

        $dataTestBill = $this->dataTest;
        $dataTestBill['id'] = 1;
        $dataTestBill2 = $this->dataTest;
        $dataTestBill2['id'] = 2;

        $dataTestStudent = $this->dataTest;
        $dataTestStudent['id'] = 4;
        $dataTestStudent2 = $this->dataTest;
        $dataTestStudent2['id'] = 5;

        $dataTestRole = $this->dataTest;
        $dataTestRole['id'] = 1;
        $dataTestRole2 = $this->dataTest;
        $dataTestRole2['id'] = 2;

        $dataTestFriend = $this->dataTest;
        $dataTestFriend['id'] = 6;
        $dataTestFriend2 = $this->dataTest;
        $dataTestFriend2['id'] = 7;

        $this->assertEquals(array_merge($this->dataTest, [
            'setting' => $this->dataTest,
            'bestFriend' => $dataTestBestFriend,
            'location' => $this->dataTest,
            'mentor' => $dataTestMentor,
            'bills' => [$dataTestBill, $dataTestBill2],
            'students' => [$dataTestStudent, $dataTestStudent2],
            'roles' => [$dataTestRole, $dataTestRole2],
            'friends' => [$dataTestFriend, $dataTestFriend2],
        ]), $data);
    }
}
