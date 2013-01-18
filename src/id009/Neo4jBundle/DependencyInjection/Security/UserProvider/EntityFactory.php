<?php
namespace id009\Neo4jBundle\DependencyInjection\Security\UserProvider;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Creates service for OGM User Provider
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class EntityFactory implements UserProviderFactoryInterface
{
	private $key;
	private $providerId;

	public function __construct($key, $providerId)
	{
		$this->key = $key;
		$this->providerId = $providerId;
	}

	public function create(ContainerBuilder $container, $id, $config)
	{
		$container->setDefinition($id, new DefinitionDecorator($this->providerId))
		          ->addArgument($config['class'])
		          ->addArgument($config['property']);
	}

	public function getKey()
	{
		return $this->key;
	}

	public function addConfiguration(NodeDefinition $node)
	{
		$node
			->children()
				->scalarNode('class')
					->isRequired()
					->cannotBeEmpty()
				->end()
				->scalarNode('property')
					->defaultNull()
				->end()
			->end();
	}
}