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
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(name: 'app:fresh')]
class FreshCommand extends Command
{
    public function __construct(
        private readonly KernelInterface $kernel,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $application->run( new ArrayInput(['command' => 'doctrine:schema:drop', '--force' => true, '--full-database' => true]), new BufferedOutput());
        $application->run( new ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]), new BufferedOutput());

        return Command::SUCCESS;
    }
}
