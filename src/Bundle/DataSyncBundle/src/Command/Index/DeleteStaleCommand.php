<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Client\Index\IndexClientRaw;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:delete-stale')]
class DeleteStaleCommand extends Command
{
    public function __construct(
        private readonly IndexClient $indexClient,
        private readonly IndexClientRaw $indexClientRaw,
        private readonly ConnectionsFetcher $connectionsFetcher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connections = $this->connectionsFetcher->fetch();

        $indexNamesAvailable = [];
        foreach ($connections as $connection) {
            foreach ($connection->getIndexes() as $index) {
                $indexNamesAvailable[$index->getName()] = $index->getConnection()->getPrefix().$index->getName();
            }

            $indexNames = $this->indexClientRaw->getAllIndexesInConnection($connection);
            foreach ($indexNames as $indexNameWithPrefix) {
                if (!in_array($indexNameWithPrefix, $indexNamesAvailable, true)) {
                    $this->indexClient->deleteIndex(new Index($connection, '', '', $indexNameWithPrefix, []));
                }
            }
        }

        return Command::SUCCESS;
    }
}
