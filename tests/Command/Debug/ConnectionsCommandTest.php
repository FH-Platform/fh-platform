<?php

namespace FHPlatform\ConfigBundle\Tests\Command\Debug;

use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefault;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;
use Symfony\Component\Console\Output\BufferedOutput;

class ConnectionsCommandTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefault::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        $this->commandHelper->runCommand(['command' => 'fh-platform:config:debug:connections'], $output = new BufferedOutput());
        $this->assertStringContainsString('Connections:', $output->fetch());
    }
}
