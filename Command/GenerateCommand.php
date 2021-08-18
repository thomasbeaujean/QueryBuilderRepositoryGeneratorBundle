<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use tbn\QueryBuilderRepositoryGeneratorBundle\Generator\RepositoryGenerator;

class GenerateCommand extends Command
{
    protected static $defaultName = 'qbrg:generate';

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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->repositoryGenerator->generateFiles();

        $output->writeln('<info>The repositories have been regenerated</info>');

        return Command::SUCCESS;
    }
}
