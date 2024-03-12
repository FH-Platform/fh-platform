<?php

namespace FHPlatform\ConfigBundle\Tests\Command\Debug;

use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use Symfony\Component\Console\Output\BufferedOutput;

class ConnectionsCommandTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderDefaultConnection::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        $this->commandHelper->runCommand(['command' => 'fh-platform:config:debug:connections'], $output = new BufferedOutput());
        $this->assertStringContainsString('Connections:', $output->fetch());
    }
}
