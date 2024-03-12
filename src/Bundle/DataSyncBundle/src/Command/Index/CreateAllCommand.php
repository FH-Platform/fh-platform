<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\IndexesFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:create-all')]
class CreateAllCommand extends Command
{
    public function __construct(
        private readonly IndexClient $indexClient,
        private readonly IndexesFetcher $indexesFetcher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $indexDtos = $this->indexesFetcher->fetch();

        foreach ($indexDtos as $indexDto) {
            /* @var Index $indexDto */
            $this->indexClient->createIndex($indexDto);
        }

        return Command::SUCCESS;
    }
}
