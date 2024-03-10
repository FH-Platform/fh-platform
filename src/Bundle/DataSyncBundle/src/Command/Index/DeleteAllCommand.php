<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexAllClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:delete-all')]
class DeleteAllCommand extends Command
{
    public function __construct(
        private readonly IndexAllClient $indexAllClient
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->indexAllClient->deleteAllIndexes();

        return Command::SUCCESS;
    }
}
