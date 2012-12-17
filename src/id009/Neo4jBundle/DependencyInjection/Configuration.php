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
                ->scalarNode('user_class')
                    ->defaultNull()
                ->end()
                ->scalarNode('proxy_dir')
                    ->defaultNull()
                ->end()
                ->scalarNode('debug')
                    ->defaultFalse()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
