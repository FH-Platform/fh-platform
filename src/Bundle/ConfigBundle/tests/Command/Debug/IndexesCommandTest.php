<?php

namespace FHPlatform\ConfigBundle\Tests\Command\Debug;

use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\RoleProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;
use Symfony\Component\Console\Output\BufferedOutput;

class IndexesCommandTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefaultConnection::class,
            RoleProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        $this->commandHelper->runCommand(['command' => 'fh-platform:config:debug:indexes'], $output = new BufferedOutput());
        $this->assertStringContainsString('Indexes:', $output->fetch());
    }
}
