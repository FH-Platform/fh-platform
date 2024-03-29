<?php

namespace App\Controller;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DebugController extends  AbstractController
{
    public function __construct(
        private readonly ConnectionsBuilder $connectionsBuilder,
    )
    {
    }

    #[Route('/_fhplatform/debug/connections')]
    public function debugConnections(): JsonResponse
    {
        return new JsonResponse(
            $this->connectionsBuilder->build()
        );
    }
}
