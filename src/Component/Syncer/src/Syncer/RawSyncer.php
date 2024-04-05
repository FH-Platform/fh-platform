<?php

namespace FHPlatform\Component\Syncer\Syncer;

use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\DTO\Document;
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
        $documents[] = $this->documentBuilder->buildRaw($className, $identifierValue, $data, Document::TYPE_CREATE);

        $documentsGrouped = (new DocumentGrouper())->groupDocuments($documents);

        $this->dataManager->syncDocuments($documentsGrouped);
    }
}
