<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:create-all')]
class CreateAllCommand extends Command
{
    public function __construct(
        private readonly IndexClient $indexClient,
        private readonly ClientBundleProvider $indexProvider,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $indexDtos = $this->indexProvider->getIndexes();

        foreach ($indexDtos as $indexDto) {
            /* @var Index $indexDto */
            $this->indexClient->createIndex($indexDto->getClassName());
        }

        return Command::SUCCESS;
    }
}
