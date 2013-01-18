<?php
namespace id009\Neo4jBundle;

use Doctrine\Common\Persistence\ManagerRegistry as ManagerRegistryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ManagerRegistry implements ManagerRegistryInterface, ContainerAwareInterface
{
	private $managers;

	private $defaultManagerName;

	private $container;

	public function __construct(array $managers, $defaultManagerName)
	{
		$this->managers = $managers;

		$this->defaultManagerName = $defaultManagerName;
	}

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	public function getDefaultManagerName()
	{
		return $this->defaultManagerName;
	}

	public function getManager($name = null)
	{
		if (null === $name){
			$name = $this->getDefaultManagerName();
		}

		if (!isset($this->managers[$name])) {
            throw new \InvalidArgumentException(sprintf('Neo4j Manager named "%s" does not exist.', $name));
        }

		return $this->container->get($this->managers[$name]);
	}

	public function getManagers()
	{
		$managers[] = array();
		foreach ($this->managers as $managerName => $managerServiceName){
			$managers[$name] = $this->container->get($managerServiceName);
		}
		
		return $managers;
	}
	
	public function resetManager($name = null)
	{
		if (null === $name){
			$name = $this->getDefaultManagerName();
		}

		if (!isset($this->managers[$name])) {
            throw new \InvalidArgumentException(sprintf('Neo4j Manager named "%s" does not exist.', $name));
        }

         $this->container->set($name, null);
	}

	public function getManagerNames()
	{
		return $this->managers;
	}

	public function getRepository($persistentObject, $persistentManagerName = null)
	{
		return $this->getManager($persistentManagerName)->getRepository($persistentObject);
	}

	public function getAliasNamespace($alias) {}

	public function getManagerForClass($class) {}

    public function getDefaultConnectionName() {}

    public function getConnection($name = null) {}

    public function getConnections() {}

    public function getConnectionNames() {}
}