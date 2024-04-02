<?php

namespace FHPlatform\Component\PersistenceEloquent\Tests\Persistence;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceEloquent\Tests\TestCase;
use FHPlatform\Component\PersistenceEloquent\Tests\Util\Entity\User;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

class PersistenceEloquentTest extends TestCase
{
    public function testHelper(): void
    {
        /** @var PersistenceInterface $persistence */
        $persistence = $this->container->get(PersistenceInterface::class);

        $capsule = new Manager();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $capsule->bootEloquent();
        $capsule->setAsGlobal();

        $capsule->getConnection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150)->nullable();
            $table->timestamps();
        });

        $user = new User();
        $user->save();

        $user2 = new User();
        $user2->save();

        $this->assertEquals('id', $persistence->getIdentifierName(User::class));
        $this->assertEquals(1, $persistence->getIdentifierValue($user));
        $this->assertEquals($user->id, $persistence->refreshByClassNameId($user::class, 1)->id);
        $this->assertEquals([1, 2], $persistence->getAllIdentifierValues($user::class));

        $entities = $persistence->getEntities(User::class, [1, 2]);

        $ids = [];
        foreach ($entities as $entity) {
            $ids[] = $entity->id;
        }

        $this->assertEquals([1, 2], $ids);
    }
}
