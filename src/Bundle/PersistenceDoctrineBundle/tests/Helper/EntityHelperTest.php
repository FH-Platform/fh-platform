<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Helper;

use FHPlatform\Bundle\PersistenceDoctrineBundle\Helper\DoctrineHelper;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\TestCase;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Entity\User;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Entity\UserUuid;

class EntityHelperTest extends TestCase
{
    public function testHelper(): void
    {
        /** @var DoctrineHelper $doctrineHelper */
        $doctrineHelper = $this->container->get(DoctrineHelper::class);

        $user = new User();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userUuid = new UserUuid();
        $this->entityManager->persist($userUuid);
        $this->entityManager->flush();

        $this->assertEquals('id', $doctrineHelper->getIdentifierName($user));
        $this->assertEquals('uuid', $doctrineHelper->getIdentifierName($userUuid));

        $this->assertEquals($user->getId(), $doctrineHelper->getIdentifierValue($user));
        $this->assertEquals($userUuid->getUuid(), $doctrineHelper->getIdentifierValue($userUuid));

        $this->assertEquals($user, $doctrineHelper->refresh($user));
        $this->assertEquals($userUuid, $doctrineHelper->refresh($userUuid));

        $this->assertEquals($user, $doctrineHelper->refreshByClassNameId(User::class, $user->getId()));
        $this->assertEquals($userUuid, $doctrineHelper->refreshByClassNameId(UserUuid::class, $userUuid->getUuid()));

        $this->assertEquals(User::class, $doctrineHelper->getRealClass("Proxies\__CG__\\".User::class));
        $this->assertEquals(UserUuid::class, $doctrineHelper->getRealClass("Proxies\__CG__\\".UserUuid::class));
    }
}
