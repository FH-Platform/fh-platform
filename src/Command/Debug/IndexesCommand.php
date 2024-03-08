<?php

namespace FHPlatform\ConfigBundle\Command\Debug;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\IndexesFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fh-platform:config:debug:indexes')]
class IndexesCommand extends Command
{
    public function __construct(
        private readonly IndexesFetcher $indexesFetcher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $indexes = $this->indexesFetcher->fetch();

        $output->writeln('Indexes:');

        foreach ($indexes as $index) {
            /* @var Index $index */

            $output->writeln('----------------------------------------------------------------------');
            $output->writeln('name='.$index->getName());
            $output->writeln('className='.$index->getClassName());
            $output->writeln('connection='.$index->getConnection()->getName());
            $output->writeln('mapping=');
            $output->writeln(json_encode($index->getMapping(), JSON_PRETTY_PRINT));
            $output->writeln('settings=');
            $output->writeln(json_encode($index->getSettings(), JSON_PRETTY_PRINT));
            $output->writeln('----------------------------------------------------------------------');
        }

        return Command::SUCCESS;
    }
}
