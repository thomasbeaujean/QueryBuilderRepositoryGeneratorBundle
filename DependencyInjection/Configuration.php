<?php

namespace tbn\QueryBuilderRepositoryGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('query_builder_repository_generator');
        $rootNode = $treeBuilder->getRootNode();

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
                            ->defaultValue('@QueryBuilderRepositoryGenerator/Generator/TopRepositoryTemplate.html.twig')
                        ->end()
                        ->scalarNode('column')
                            ->defaultValue( '@QueryBuilderRepositoryGenerator/Generator/ColumnTemplate.html.twig')
                        ->end()
                        ->scalarNode('association')
                            ->defaultValue('@QueryBuilderRepositoryGenerator//Generator/AssociationTemplate.html.twig')
                        ->end()
                        ->scalarNode('bottom_repository')
                            ->defaultValue('@QueryBuilderRepositoryGenerator/Generator/BottomRepositoryTemplate.html.twig')
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
                ->arrayNode('repositories_extensions')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('extension_class')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
