<?php
namespace id009\Neo4jBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CreateProxyDirectoryPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		$proxyDir = $container->getParameter('id009_neo4j.proxy_dir');
		if (!is_dir($proxyDir)){
			if (false === @mkdir($proxyDir, 0777, true)){
				exit(sprintf('Unable to create the Neo4j OGM proxy directory %s', $proxyDir));
			}
		} elseif (!is_writable($proxyDir)){
			exit(sprintf('Unable to write int the Neo4j OGM directory %s', $proxyDir));
		}
	}
}