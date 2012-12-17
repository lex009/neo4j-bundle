<?php
namespace id009\Neo4jBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use HireVoice\Neo4j\EntityManager;

/**
 * Compiller pass for adding events
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class EventManagerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('id009_neo4j.entity_manager')){
			return;
		}

		$definition = $container->getDefinition('id009_neo4j.entity_manager');

		$subscribers = $container->findTaggedServiceIds('id009_neo4j.subscriber');

		foreach ($subscribers as $id => $attributes){
			$definition->addMethodCall('addSubscriber', array(new Reference($id)));
		}
	}
}