<?php

namespace id009\Neo4jBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class id009Neo4jExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('id009_neo4j.server.host', $config['host']);
        $container->setParameter('id009_neo4j.server.port', $config['port']);
        $container->setParameter('id009_neo4j.server.username', $config['username']);
        $container->setParameter('id009_neo4j.server.password', $config['password']);
        $container->setParameter('id009_neo4j.secutiry.user_class', $config['user_class']);
        $container->setParameter('id009_neo4j.debug', $config['debug']);

        if (null === $config['proxy_dir']){
            $proxy_dir = $container->getParameter('kernel.cache_dir').'/doctrine/neo4j/Proxies';
        } else {
            $proxy_dir = $config['proxy_dir'];
        }

        $container->setParameter('id009_neo4j.proxy_dir', $proxy_dir);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (true === $config['debug']){
            $collectorSubscriber = $container->getDefinition('id009_neo4j.event.data_collector');
            $collectorSubscriber->addTag('id009_neo4j.subscriber');
            $collectorSubscriber->addMethodCall('setCollector', array(new Reference('id009_neo4j.data_collector')));
        }
    }
}
