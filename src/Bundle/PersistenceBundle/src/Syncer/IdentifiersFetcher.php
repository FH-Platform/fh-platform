<?php

namespace FHPlatform\Bundle\PersistenceBundle\Syncer;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\Bundle\UtilBundle\Helper\EntityHelper;

class IdentifiersFetcher
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EntityHelper $entityHelper,
    ) {
    }

    public function fetch(string $className): array
    {
        // TODO add to config
        $maxResults = 10;

        $firstResult = 0;
        $identifiers = [];
        while (true) {
            // TODO order by identifier
            $query = $this->entityManager->createQuery(
                'SELECT e FROM '.$className.' e '.
                ' ORDER BY e.id DESC'
            )
                ->setMaxResults($maxResults)
                ->setFirstResult($firstResult);

            $entitiesBatch = $query->getResult();

            // when we reach batch where there are no results break while(true)
            if (0 === count($entitiesBatch)) {
                break;
            }

            $identifiersBatch = [];
            foreach ($entitiesBatch as $entityBatch) {
                $identifier = $this->entityHelper->getIdentifierValue($entityBatch);

                // store identifiers from each batch
                // TODO detect $shouldBeIndexed
                $identifiers[] = $identifier;
            }

            // increase firstResult(offset)
            $firstResult += $maxResults;

            $this->entityManager->clear();
        }

        return $identifiers;
    }
}
