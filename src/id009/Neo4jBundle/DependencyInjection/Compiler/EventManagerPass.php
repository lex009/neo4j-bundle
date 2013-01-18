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
		$subscribers = $container->findTaggedServiceIds('id009_neo4j.subscriber');

		foreach ($subscribers as $id => $tagAttributes){
			foreach ($tagAttributes as $attributes){
				if (!isset($attributes['manager'])) $attributes['manager'] = $container->getParameter('id009_neo4j.default_entity_manager_name');

				$managerId = sprintf('id009_neo4j.%s_entity_manager', $attributes['manager']);
			
				if (!$container->hasDefinition($managerId)){
					continue;
				} else {
					$definition = $container->getDefinition($managerId);
				}

				$definition->addMethodCall('addSubscriber', array(new Reference($id)));
			}
		}
	}
}