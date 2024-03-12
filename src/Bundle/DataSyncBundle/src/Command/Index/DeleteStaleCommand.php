<?php

namespace FHPlatform\DataSyncBundle\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexNameClient;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:index:delete-stale')]
class DeleteStaleCommand extends Command
{
    public function __construct(
        private readonly IndexNameClient $indexNameClient,
        private readonly ClientBundleProvider $clientBundleProvider,
        private readonly TaggedProvider $taggedProvider,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionProvider = $this->taggedProvider->firstConnectionProvider();

        $indexDtos = $this->clientBundleProvider->getIndexes();

        $indexNamesAvailable = [];

        foreach ($indexDtos as $indexDto) {
            /* @var Index $indexDto */
            $indexNamesAvailable[] = $connectionProvider->getIndexPrefix().$indexDto->getName();
        }

        $indexNames = $this->indexNameClient->getIndexesNameByPrefix();
        foreach ($indexNames as $indexName) {
            if (!in_array($indexName, $indexNamesAvailable, true)) {
                $this->indexNameClient->deleteIndexByName($indexName);
            }
        }

        return Command::SUCCESS;
    }
}
