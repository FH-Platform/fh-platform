<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Command\Data;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fhplatform:data:sync')]
class SyncCommand extends Command
{
    public function __construct(
        // private readonly DataSyncer $dataSyncer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('class-name', InputArgument::REQUIRED, 'Class Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('class-name');

        // TODO check if class exists
        // $this->dataSyncer->sync($className);

        return Command::SUCCESS;
    }
}
