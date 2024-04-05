<?php

namespace FHPlatform\Component\Syncer\Syncer;

use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use FHPlatform\Component\Syncer\DocumentGrouper;

class RawSyncer
{
    public function __construct(
        private readonly DocumentBuilder $documentBuilder,
        private readonly DataManager $dataManager,
    ) {
    }

    // TODO test it ...
    public function syncRawAction(string $className, array $data, mixed $identifierValue): void
    {
        $documents[] = $this->documentBuilder->buildRaw($className, $identifierValue, $data);

        $documentsGrouped = (new DocumentGrouper())->groupDocuments($documents);

        $this->dataManager->syncDocuments($documentsGrouped);
    }
}
