<?php

namespace FHPlatform\ConfigBundle\Tests\Command\Debug;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Entity\Role;
use FHPlatform\ConfigBundle\Tests\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefault;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\RoleProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;
use Symfony\Component\Console\Output\BufferedOutput;

class EntityRelatedCommandTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefault::class,
            UserProviderEntity::class,
            RoleProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        $role = new Role();
        $role->setNameString('test');

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        $user = new User();
        $user->setNameString('test');
        $user->setRoles(new ArrayCollection([$role]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->commandHelper->runCommand(['command' => 'fh-platform:config:debug:entity-related', 'class-name' => Role::class, 'identifier' => 1], $output = new BufferedOutput());
        $this->assertStringContainsString('Entity related:', $output->fetch());
    }
}
