<?php

namespace App\Console;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'app:seed')]
class SeedCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $role = new Role();
        $role->setNameString('first');
        $this->entityManager->persist($role);

        $role2 = new Role();
        $role2->setNameString('second');
        $this->entityManager->persist($role2);

        $this->entityManager->flush();

        $roles = [$role, $role2];

        for ($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setNameString('name_string_' . Uuid::uuid1());
            $user->setNameText('name_text_' . Uuid::uuid1());

            $user->setRoles(new ArrayCollection(array_splice($roles, random_int(0, 2))));

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
