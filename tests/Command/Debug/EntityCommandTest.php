<?php

namespace FHPlatform\ConfigBundle\Tests\Command\Debug;

use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Entity\Role;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefault;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\RoleProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;
use Symfony\Component\Console\Output\BufferedOutput;

class EntityCommandTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefault::class,
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

        $this->commandHelper->runCommand(['command' => 'fh-platform:config:debug:entity', 'class-name' => Role::class, 'identifier' => 1], $output = new BufferedOutput());
        $this->assertStringContainsString('Entity:', $output->fetch());
    }
}
