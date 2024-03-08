<?php

namespace FHPlatform\ConfigBundle\Command\Debug;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fh-platform:config:debug:entity')]
class EntityCommand extends Command
{
    public function __construct(
        private readonly EntityFetcher $entityFetcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('class-name', InputArgument::REQUIRED, 'Class Name');
        $this->addArgument('identifier', InputArgument::REQUIRED, 'Identifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('class-name');
        $identifier = $input->getArgument('identifier');

        $entity = $this->entityManager->getRepository($className)->find($identifier);

        $entity = $this->entityFetcher->fetch($entity);

        $output->writeln('Entity:');
        $output->writeln('----------------------------------------------------------------------');
        $output->writeln('index='.$entity->getIndex()->getName());
        $output->writeln('should_be_indexed='.$entity->getShouldBeIndexed());
        $output->writeln('data=');
        $output->writeln(json_encode($entity->getData(), JSON_PRETTY_PRINT));
        $output->writeln('----------------------------------------------------------------------');

        return Command::SUCCESS;
    }
}
