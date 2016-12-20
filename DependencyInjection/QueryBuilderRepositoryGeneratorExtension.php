<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class QueryBuilderRepositoryGeneratorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        //the bundles to load
        $container->setParameter('tbn_qbrg.generator.bundles', $config['bundles']);

        $entityConfiguration = [];
        foreach ($config['mapping'] as $entityConfig) {
            foreach ($entityConfig as $entityAlias => $configuration) {
                $entityConfiguration[$entityAlias] = $configuration;
            }
        }

        $repositoryExtensions = [];

        if (isset($config['repositories_extensions'])) {
            foreach ($config['repositories_extensions'] as $extension) {
                $keys = array_keys($extension);
                $entityClass = $keys[0];
                $extends = $extension[$entityClass];
                $repositoryExtensions[$entityClass] = $extends;
            }
        }

        $container->setParameter('tbn_qbrg.generator.template.entity_configuration', $entityConfiguration);
        $container->setParameter('tbn_qbrg.generator.template.repository_extension', $repositoryExtensions);

        //the templates
        $container->setParameter('tbn_qbrg.generator.template.top_repository', $config['templates']['top_repository']);
        $container->setParameter('tbn_qbrg.generator.template.column', $config['templates']['column']);
        $container->setParameter('tbn_qbrg.generator.template.association', $config['templates']['association']);
        $container->setParameter('tbn_qbrg.generator.template.bottom_repository', $config['templates']['bottom_repository']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
