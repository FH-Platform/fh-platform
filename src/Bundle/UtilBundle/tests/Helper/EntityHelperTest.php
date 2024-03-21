<?php

namespace FHPlatform\Bundle\UtilBundle\Tests\Helper;

use FHPlatform\Bundle\UtilBundle\Helper\EntityHelper;
use FHPlatform\Bundle\UtilBundle\Tests\TestCase;
use FHPlatform\Bundle\UtilBundle\Tests\Util\Entity\User;
use FHPlatform\Bundle\UtilBundle\Tests\Util\Entity\UserUuid;

class EntityHelperTest extends TestCase
{
    public function testHelper(): void
    {
        /** @var EntityHelper $entityHelper */
        $entityHelper = $this->container->get(EntityHelper::class);

        $user = new User();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userUuid = new UserUuid();
        $this->entityManager->persist($userUuid);
        $this->entityManager->flush();

        $this->assertEquals('id', $entityHelper->getIdentifierName($user));
        $this->assertEquals('uuid', $entityHelper->getIdentifierName($userUuid));

        $this->assertEquals($user->getId(), $entityHelper->getIdentifierValue($user));
        $this->assertEquals($userUuid->getUuid(), $entityHelper->getIdentifierValue($userUuid));

        $this->assertEquals($user, $entityHelper->refresh($user));
        $this->assertEquals($userUuid, $entityHelper->refresh($userUuid));

        $this->assertEquals($user, $entityHelper->refreshByClassNameId(User::class, $user->getId()));
        $this->assertEquals($userUuid, $entityHelper->refreshByClassNameId(UserUuid::class, $userUuid->getUuid()));

        $this->assertEquals(User::class, $entityHelper->getRealClass("Proxies\__CG__\\".User::class));
        $this->assertEquals(UserUuid::class, $entityHelper->getRealClass("Proxies\__CG__\\".UserUuid::class));
    }
}
