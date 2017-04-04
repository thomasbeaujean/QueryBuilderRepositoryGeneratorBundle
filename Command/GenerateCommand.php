<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use tbn\QueryBuilderRepositoryGeneratorBundle\Generator\RepositoryGenerator;

/**
 *
 */
class GenerateCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('qbrg:generate');
        $this->setDescription('Regenerate the Base Repository with the Query Builder Repository Generator');
    }

    /**
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $generator RepositoryGenerator */
        $generator = $this->getContainer()->get('tbn_qbrg.generator.repository_generator');
        $generator->generateFiles();

        $output->writeln('<info>The repositories have been regenerated</info>');
    }
}
