<?php

namespace App\Console;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $role->setTestString('first');
        $this->entityManager->persist($role);

        $role2 = new Role();
        $role2->setTestString('second');
        $this->entityManager->persist($role2);

        $this->entityManager->flush();

        $roles = [[$role, $role2], [$role], [$role2]];

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setTestString('name_string_'.Uuid::uuid1());

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
