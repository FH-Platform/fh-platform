<?php

namespace App\Controller;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DebugController extends AbstractController
{
    public function __construct(
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    #[Route('/_fhplatform/debug/connections')]
    public function debugConnections(): JsonResponse
    {
        $connections = (array) $this->connectionsBuilder->build();

        $arr = json_decode(json_encode($connections), true);

        $connectionsArray = [];

        foreach ($connections as $connection) {
            $connectionArray = $this->object_to_array($connection);

            $indexesArray = [];
            $indexes = $connection->getIndexes();
            foreach ($indexes as $index) {
                $indexArray = $this->object_to_array($index);

                unset($indexArray['connection']);

                $indexesArray[] = $indexArray;
            }

            $connectionArray['indexes'] = $indexesArray;

            $connectionsArray[] = $connectionArray;
        }

        return new JsonResponse(
            $connectionsArray
        );
    }

    private function object_to_array($object)
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        $array = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }

        return $array;
    }
}
