<?php

namespace id009\Neo4jBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('id009_neo4j');

        $rootNode
            ->children()
                ->scalarNode('proxy_dir')
                    ->defaultValue('%kernel.cache_dir%'.'/doctrine/neo4j/Proxies')
                ->end()
                ->arrayNode('connections')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('host')
                             ->defaultValue('localhost')
                        ->end()
                        ->scalarNode('port')
                            ->defaultValue('7474')
                        ->end()
                        ->scalarNode('username')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('password')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()

                ->arrayNode('entity_managers')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('debug')
                            ->defaultValue('%kernel.debug%')
                        ->end()
                        ->scalarNode('pathfinder_algorithm')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('pathfinder_maxdepth')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
