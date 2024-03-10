<?php

namespace FHPlatform\DataSyncBundle\Command\Debug;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:debug:entity')]
class EntityCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly IndexFetcher $indexFetcher,
        private readonly EntityFetcher $entityFetcher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('class-name', InputArgument::REQUIRED, 'Class Name');
        $this->addArgument('id', InputArgument::REQUIRED, 'Id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('class-name');
        $id = $input->getArgument('id');

        $entity = $this->entityManager->getRepository($className)->find($id);

        $name = $this->indexFetcher->fetch($entity::class)->getName();
        $mapping = $this->indexFetcher->fetch($entity::class)->getMapping();
        $settings = $this->indexFetcher->fetch($entity::class)->getSettings();
        $data = $this->entityFetcher->fetch($entity)->getData();

        $output->writeln('Name:'.$name);
        $output->writeln(json_encode($mapping, JSON_PRETTY_PRINT));
        $output->writeln('Mapping:');
        $output->writeln(json_encode($mapping, JSON_PRETTY_PRINT));
        $output->writeln('Settings:');
        $output->writeln(json_encode($settings, JSON_PRETTY_PRINT));
        $output->writeln('Data:');
        $output->writeln(json_encode($data, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
