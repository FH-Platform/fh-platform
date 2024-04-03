<?php

namespace FHPlatform\Component\FilterToEsDsl\Result\Converter;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\FilterToEsDsl\Result\DTO\ResultDto;
use FHPlatform\Component\FilterToEsDsl\Result\DTO\ResultItemDto;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class ResultsConverter
{
    public const TYPE_ENTITIES = 'entities';
    public const TYPE_RAW_WITH_ENTITIES = 'raw_with_entities';
    public const TYPE_DTO = 'dto';

    public function __construct(
        private readonly QueryManager $queryManager,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function getResults(Index $index, mixed $queryBase, string $type = QueryManager::TYPE_IDENTIFIERS): mixed
    {
        if (in_array($type, [QueryManager::TYPE_RAW, QueryManager::TYPE_IDENTIFIERS, QueryManager::TYPE_SOURCES])) {
            return $this->queryManager->getResults($index, $queryBase, $type);
        }

        $identifiers = $this->queryManager->getResults($index, $queryBase, QueryManager::TYPE_IDENTIFIERS);

        if (self::TYPE_ENTITIES === $type) {
            return $this->persistence->getEntities($index->getClassName(), $identifiers);
        } elseif (self::TYPE_RAW_WITH_ENTITIES === $type) {
            $rawResult = $this->queryManager->getResults($index, $queryBase, QueryManager::TYPE_RAW);

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            $entitiesByIds = [];
            foreach ($entities as $entity) {
                $identifierValue = $this->persistence->getIdentifierValue($entity);
                $entitiesByIds[$identifierValue] = $entity;
            }

            foreach ($rawResult['hits']['hits'] as $key => $result) {
                $rawResult['hits']['hits'][$key]['_entity'] = $entitiesByIds[$result['_id']] ?? null;
            }

            return $rawResult;
        } elseif (self::TYPE_DTO === $type) {
            $rawResult = $this->queryManager->getResults($index, $queryBase, QueryManager::TYPE_RAW);

            $hits = $rawResult['hits']['hits'];
            unset($rawResult['hits']['hits']);

            $items = [];
            foreach ($hits as $hit) {
                $entity = $this->persistence->refreshByClassNameId($index->getClassName(), $hit['_id']);
                $items[] = new ResultItemDto($hit, $entity);
            }

            $resultDTO = new ResultDto($rawResult, $items);

            return $resultDTO;
        }

        throw new \Exception('Unsupported type');
    }
}
