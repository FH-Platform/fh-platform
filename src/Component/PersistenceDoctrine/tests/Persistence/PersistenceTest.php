<?php

namespace FHPlatform\Component\PersistenceDoctrine\Tests\Persistence;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\UserUuid;
use FHPlatform\Component\PersistenceDoctrine\DoctrinePersistence;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class PersistenceTest extends TestCase
{
    public function testHelper(): void
    {
        /** @var DoctrinePersistence $persistenceDoctrine */
        $persistenceDoctrine = $this->container->get(DoctrinePersistence::class);

        $user = new User();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userUuid = new UserUuid();
        $this->entityManager->persist($userUuid);
        $this->entityManager->flush();

        $user2 = new User();
        $this->entityManager->persist($user2);

        // getIdentifierName()
        $this->assertEquals('id', $persistenceDoctrine->getIdentifierName(User::class));
        $this->assertEquals(null, $persistenceDoctrine->getIdentifierName('test'));

        // getIdentifierValue()
        $this->assertEquals($user->getId(), $persistenceDoctrine->getIdentifierValue($user));
        $this->assertEquals($userUuid->getUuid(), $persistenceDoctrine->getIdentifierValue($userUuid));
        $this->assertEquals(null, $persistenceDoctrine->getIdentifierValue(new User()));
        $this->assertEquals(null, $persistenceDoctrine->getIdentifierValue($user2));
        $this->assertEquals(null, $persistenceDoctrine->getIdentifierValue('test'));

        // refreshByClassNameId()
        $this->assertEquals($user, $persistenceDoctrine->refreshByClassNameId(User::class, $user->getId()));
        $this->assertEquals($userUuid, $persistenceDoctrine->refreshByClassNameId(UserUuid::class, $userUuid->getUuid()));
        $this->assertEquals(null, $persistenceDoctrine->refreshByClassNameId(User::class, 2));
        // $this->assertEquals(null, $persistenceDoctrine->refreshByClassNameId('test', 2));

        // refresh()
        $this->assertEquals($user, $persistenceDoctrine->refresh($user));
        $this->assertEquals($userUuid, $persistenceDoctrine->refresh($userUuid));
        $this->assertEquals(null, $persistenceDoctrine->refresh('test'));
        $this->assertEquals(null, $persistenceDoctrine->refresh($user2));
        $this->assertEquals(null, $persistenceDoctrine->refresh(new User()));

        // getRealClassName()
        $this->assertEquals(User::class, $persistenceDoctrine->getRealClassName("Proxies\__CG__\\".User::class));
        $this->assertEquals(UserUuid::class, $persistenceDoctrine->getRealClassName("Proxies\__CG__\\".UserUuid::class));

        // isClassNamePersistence()
        $this->assertEquals(true, $persistenceDoctrine->isClassNamePersistence(User::class));
        $this->assertEquals(false, $persistenceDoctrine->isClassNamePersistence('test'));

        // getEntities()
        $this->entityManager->flush();
        $this->assertEquals([], $this->entitiesToIds($persistenceDoctrine->getEntities(User::class, [])));
        $this->assertEquals([1], $this->entitiesToIds($persistenceDoctrine->getEntities(User::class, [1])));
        $this->assertEquals([1, 2], $this->entitiesToIds($persistenceDoctrine->getEntities(User::class, [1, 2])));
        $this->assertEquals([2, 1], $this->entitiesToIds($persistenceDoctrine->getEntities(User::class, [2, 1])));

        // getAllIdentifierValues()
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $this->save([$user]);
        }

        $this->assertEquals([12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1], $persistenceDoctrine->getAllIdentifierValues(User::class));
    }

    private function entitiesToIds($entities): array
    {
        $ids = [];

        foreach ($entities as $entity) {
            $ids[] = $entity->getId();
        }

        return $ids;
    }
}
