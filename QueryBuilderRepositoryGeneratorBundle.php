<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use tbn\QueryBuilderRepositoryGeneratorBundle\ClassLoader\GeneratedFilesLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Container;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class QueryBuilderRepositoryGeneratorBundle extends Bundle
{
    /**
     * @var boolean
     */
    private $classLoaderInitialized = false;

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::boot()
     */
    public function boot()
    {
        $this->initAdmingeneratorClassLoader($this->container);
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     */
    public function build(ContainerBuilder $container)
    {
        $this->initAdmingeneratorClassLoader($container);
        parent::build($container);
    }

    /**
     * Initialize Class loader
     *
     * @param ContainerBuilder $container
     */
    private function initAdmingeneratorClassLoader(Container $container)
    {
        if (!$this->classLoaderInitialized) {
            $this->classLoaderInitialized = true;
            $AdmingeneratedClassLoader = new GeneratedFilesLoader();
            $AdmingeneratedClassLoader->setBasePath($container->getParameter('kernel.cache_dir'));//.'/tbn\QueryBuilderRepositoryGeneratorBundle\Repository');
            $AdmingeneratedClassLoader->register();
        }
    }
}
