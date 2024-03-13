<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:delete-all')]
class DeleteAllCommand extends Command
{
    public function __construct(
        private readonly IndexClient $indexClient,
        private readonly ConnectionsFetcher $connectionsFetcher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connections = $this->connectionsFetcher->fetch();

        foreach ($connections as $connection) {
            foreach ($connection->getIndexes() as $index) {
                $this->indexClient->deleteIndex($index);
            }
        }

        return Command::SUCCESS;
    }
}
