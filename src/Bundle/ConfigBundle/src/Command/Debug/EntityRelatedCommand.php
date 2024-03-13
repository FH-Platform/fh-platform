<?php

namespace FHPlatform\ConfigBundle\Command\Debug;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\Fetcher\Entity\EntityRelatedFetcher;
use FHPlatform\UtilBundle\Helper\EntityHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fh-platform:config:debug:entity-related')]
class EntityRelatedCommand extends Command
{
    public function __construct(
        private readonly EntityRelatedFetcher $entityRelatedFetcher,
        private readonly EntityManagerInterface $entityManager,
        private readonly EntityHelper $entityHelper,
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

        $entitiesRelated = $this->entityRelatedFetcher->fetch($entity);

        $output->writeln('Entity related:');
        $output->writeln('----------------------------------------------------------------------');
        foreach ($entitiesRelated as $entityRelated) {
            $output->writeln($this->entityHelper->getIdentifierValue($entityRelated).' -> '.$this->entityHelper->getRealClass($entityRelated::class));
        }
        $output->writeln('----------------------------------------------------------------------');

        return Command::SUCCESS;
    }
}
