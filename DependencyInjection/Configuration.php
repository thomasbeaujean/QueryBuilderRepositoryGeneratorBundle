<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Config\Definition\ConfigurationInterface::getConfigTreeBuilder()
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('query_builder_repository_generator');

        $rootNode
            ->children()
                ->arrayNode('bundles')
                ->isRequired()
                ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('top_repository')
                            ->defaultValue("QueryBuilderRepositoryGeneratorBundle:Generator:TopRepositoryTemplate.html.twig")
                        ->end()
                        ->scalarNode('column')
                            ->defaultValue("QueryBuilderRepositoryGeneratorBundle:Generator:ColumnTemplate.html.twig")
                        ->end()
                        ->scalarNode('extra_column')
                            ->defaultValue('')
                        ->end()
                        ->scalarNode('bottom_repository')
                            ->defaultValue("QueryBuilderRepositoryGeneratorBundle:Generator:BottomRepositoryTemplate.html.twig")
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mapping')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('querybuilder_name')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
