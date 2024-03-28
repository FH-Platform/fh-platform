<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Command\Index;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fhplatform:index:delete-stale')]
class DeleteStaleCommand extends Command
{
    public function __construct(
        private readonly IndexManager $indexClient,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connections = $this->connectionsBuilder->build();

        $indexNamesAvailable = [];
        foreach ($connections as $connection) {
            foreach ($connection->getIndexes() as $index) {
                $indexNamesAvailable[$index->getName()] = $index->getConnection()->getPrefix().$index->getName();
            }

            $indexNames = $this->indexClient->getAllIndexesInConnection($connection);
            foreach ($indexNames as $indexNameWithPrefix) {
                if (!in_array($indexNameWithPrefix, $indexNamesAvailable, true)) {
                    $this->indexClient->deleteIndex(new Index($connection, '', false, '', $indexNameWithPrefix, []));
                }
            }
        }

        return Command::SUCCESS;
    }
}
