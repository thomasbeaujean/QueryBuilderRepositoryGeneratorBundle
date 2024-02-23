<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use tbn\QueryBuilderRepositoryGeneratorBundle\Generator\RepositoryGenerator;

#[AsCommand(
    name: 'qbrg:generate',
)]
class GenerateCommand extends Command
{
    /** @var RepositoryGenerator  */
    private $repositoryGenerator;

    public function __construct(RepositoryGenerator $repositoryGenerator)
    {
        parent::__construct();
        $this->repositoryGenerator = $repositoryGenerator;
    }

    protected function configure()
    {
        $this->setDescription('Regenerate the Base Repository with the Query Builder Repository Generator');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->repositoryGenerator->generateFiles();

        $output->writeln('<info>The repositories have been regenerated</info>');

        return Command::SUCCESS;
    }
}
