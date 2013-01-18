<?php

namespace id009\Neo4jBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class id009Neo4jExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('id009_neo4j.proxy_dir', $config['proxy_dir']);

        if (empty($config['default_entity_manager'])){
            $keys = array_keys($config['entity_managers']);
            $config['default_entity_manager'] = reset($keys);
        }

        $container->setParameter('id009_neo4j.default_entity_manager_name', $config['default_entity_manager']);

        $managersArray = array();
        foreach ($config['entity_managers'] as $managerName => $managerConfig){
            $managerConfig['name'] = $managerName;
            $this->loadEntityManager($managerConfig, $config['connections'], $config['default_entity_manager'], $container);

            $managersArray[$managerName] = sprintf('id009_neo4j.%s_entity_manager', $managerName);
        }

        $container->setParameter('id009_neo4j.entity_managers', $managersArray);
    }

    private function loadEntityManager(array $manager, array $connections, $defaultManager, ContainerBuilder $container)
    {
        $connectionName = isset($manager['connection']) ? $manager['connection'] : $manager['name'];
        if (!isset($connections[$connectionName])){
            throw new RuntimeException(sprintf('You did not set up connection "%s" for entity manager "%s"', $connectionName, $manager['name']));
        }
        $connection = $connections[$connectionName];

        $configArray = array(
            'host'                 => $connection['host'],
            'port'                 => $connection['port'],
            'username'             => $connection['username'],
            'password'             => $connection['password'],
            'debug'                => $manager['debug'],
            'proxy_dir'            => $container->getParameter('id009_neo4j.proxy_dir'),
            'pathfinder_algorithm' => $manager['pathfinder_algorithm'],
            'pathfinder_maxdepth'  => $manager['pathfinder_maxdepth'],
        );
        
        $configServiceName = sprintf('id009_neo4j.%s_configuration', $manager['name']);
        $configDefinition = new Definition('%id009_neo4j.configuration.class%', array($configArray));
        $container->setDefinition($configServiceName, $configDefinition);

        $managerServiceName = sprintf('id009_neo4j.%s_entity_manager', $manager['name']);

        $managerDefinition = new Definition('%id009_neo4j.entity_manager.class%', array(new Reference($configServiceName)));
        $container->setDefinition($managerServiceName, $managerDefinition);

        if ($manager['name'] == $defaultManager){
            $container->setAlias('id009_neo4j.entity_manager', new Alias($managerServiceName));
        }

        if (true === $manager['debug']){
            $subscriberServiceName = sprintf('id009_neo4j.event.%s_data_collector', $manager['name']);
            $subscriberDefinition = new Definition('%id009_neo4j.event.data_collector.class%');
            $subscriberDefinition->addTag('id009_neo4j.subscriber', array('manager' => $manager['name']));
            $subscriberDefinition->addMethodCall('setCollector', array(new Reference('id009_neo4j.data_collector')));
            $container->setDefinition($subscriberServiceName, $subscriberDefinition);
        }
    }
}