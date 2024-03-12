<?php

namespace FHPlatform\ConfigBundle\Command\Debug;

use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fh-platform:config:debug:connections')]
class ConnectionsCommand extends Command
{
    public function __construct(
        private readonly ConnectionsFetcher $connectionsFetcher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connections = $this->connectionsFetcher->fetch();

        $output->writeln('Connections:');

        foreach ($connections as $connection) {
            /* @var Connection $connection */

            $output->writeln('----------------------------------------------------------------------');
            $output->writeln('name='.$connection->getName());
            $output->writeln('prefix='.$connection->getPrefix());
            $output->writeln('config=');
            $output->writeln(json_encode($connection->getElasticaConfig(), JSON_PRETTY_PRINT));
            $output->writeln('----------------------------------------------------------------------');
        }

        return Command::SUCCESS;
    }
}
