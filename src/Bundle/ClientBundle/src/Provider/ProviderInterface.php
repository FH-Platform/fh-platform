<?php

namespace FHPlatform\ClientBundle\Provider;

use FHPlatform\ConfigBundle\DTO\Index;

interface ProviderInterface
{
    public function documentPrepare(Index $index, mixed $identifier, array $data);

    public function documentsUpsert(Index $index, mixed $documents);

    public function documentsDelete(Index $index, mixed $documents);

    public function indexRefresh(Index $index): mixed;

    public function searchPrepare(Index $index, mixed $query = null): mixed;

    public function searchResults(Index $index, mixed $query = null, $limit = null, $offset = 0): mixed;
}
