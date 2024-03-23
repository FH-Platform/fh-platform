<?php

namespace FHPlatform\Component\ClientElastica\Tests\Data;

use Elastica\Query;
use FHPlatform\Component\Client\Provider\Data\DataClient;
use FHPlatform\Component\Client\Provider\Index\IndexClient;
use FHPlatform\Component\Client\Tests\TestCase;
use FHPlatform\Component\Client\Tests\Util\Entity\Log;
use FHPlatform\Component\Client\Tests\Util\Entity\Role;
use FHPlatform\Component\Client\Tests\Util\Entity\User;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Entity;
use FHPlatform\Component\Config\DTO\Index;

class DataClientTest extends \FHPlatform\Component\Client\Tests\Data\DataClientTest
{
    protected bool $testingClient = true;
}
