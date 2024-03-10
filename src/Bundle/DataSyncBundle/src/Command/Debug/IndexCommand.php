<?php

namespace FHPlatform\DataSyncBundle\Command\Debug;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\IndexesFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'symfony-es:debug:index')]
class IndexCommand extends Command
{
    public function __construct(
        private readonly IndexesFetcher $indexesFetcher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('all', InputArgument::OPTIONAL, 'All');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $all = $input->getArgument('all');

        foreach ($this->indexesFetcher->fetch() as $index) {
            /* @var Index $index */
            $output->writeln('className="'.$index->getClassName().'", name="'.$index->getName().'", connection="'.$index->getConnection()->getName());
        }

        return Command::SUCCESS;
    }
}
