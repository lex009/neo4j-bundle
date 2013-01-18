<?php

namespace id009\Neo4jBundle;

use id009\Neo4jBundle\DependencyInjection\Compiler\CreateProxyDirectoryPass;
use id009\Neo4jBundle\DependencyInjection\Compiler\EventManagerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Bridge\Doctrine\DependencyInjection\Security\UserProvider\EntityFactory;

/**
 * Neo4j Symfony bundle
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class id009Neo4jBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new EventManagerPass());
		$container->addCompilerPass(new CreateProxyDirectoryPass(), PassConfig::TYPE_BEFORE_REMOVING);

		if ($container->hasExtension('security')) {
        	$container->getExtension('security')->addUserProviderFactory(new EntityFactory('neo4j', 'id009_neo4j.security.provider'));
        }
	}
}
