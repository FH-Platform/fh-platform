<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Command\Index;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\SearchEngine\Provider\Index\IndexClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:delete-all')]
class DeleteAllCommand extends Command
{
    public function __construct(
        private readonly IndexClient $indexClient,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connections = $this->connectionsBuilder->build();

        foreach ($connections as $connection) {
            foreach ($connection->getIndexes() as $index) {
                $this->indexClient->deleteIndex($index);
            }
        }

        return Command::SUCCESS;
    }
}
