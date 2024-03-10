<?php

namespace FHPlatform\ClientBundle\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandHelper
{
    public function __construct(
        private readonly KernelInterface $kernel,
    ) {
    }

    public function runCommand($data, ?OutputInterface $output = null): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        if (!$output) {
            // $output = new BufferedOutput();
        }

        $application->run(new ArrayInput($data), $output);
    }
}
