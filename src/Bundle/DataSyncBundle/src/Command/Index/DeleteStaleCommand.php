<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexClientRaw;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:delete-stale')]
class DeleteStaleCommand extends Command
{
    public function __construct(
        private readonly IndexClientRaw $indexClientRaw,
        private readonly ClientBundleProvider $clientBundleProvider,
        private readonly TaggedProvider $taggedProvider,
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

            $indexNames = $this->indexClientRaw->getIndexesNameByPrefix($connection);
            foreach ($indexNames as $indexName) {
                if (!in_array($indexName, $indexNamesAvailable, true)) {
                    $this->indexClientRaw->deleteIndexByName($connection, str_replace($connection->getPrefix(), '', $indexName));
                }
            }
        }

        return Command::SUCCESS;
    }
}
