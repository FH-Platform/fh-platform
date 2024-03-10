<?php

namespace FHPlatform\DataSyncBundle\Tests\Command\Debug;

use FHPlatform\DataSyncBundle\Tests\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class IndexCommandTest extends TestCase
{
    public function testSomething(): void
    {
        $this->commandHelper->runCommand(['command' => 'symfony-es:debug:index', 'all' => 1], $output = new BufferedOutput());
        $this->assertStringContainsString('className="FHPlatform\DataSyncBundle\Tests\Util\Entity\User", name="user", connection="default', $output->fetch());

        $this->assertEquals(1, 1);
    }
}
