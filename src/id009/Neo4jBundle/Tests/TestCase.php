<?php
namespace id009\Neo4jBundle\Tests;

use id009\Neo4jBundle\EntityManager;
use id009\Neo4jBundle\ManagerRegistry;
use HireVoice\Neo4j\Configuration;
use Doctrine\Common\Persistence\ManagerRegistry as ManagerRegistryInterface;

class TestCase extends \PHPUnit_Framework_TestCase
{
	public static function getEntityManager()
	{
		$configuration = new Configuration(
			array(
				'host' => $GLOBALS['host'],
				'port' => $GLOBALS['port'],
				'proxy_dir' => \sys_get_temp_dir(),
			)	
		);
		return new EntityManager($configuration);
	}

	public function createRegistry($em, $name = 'default')
	{
		$registry = $this->getMock('id009\Neo4jBundle\ManagerRegistry', array('getManager'), array(array($name => $em), $name));
		$registry->expects($this->any())
				 ->method('getManager')
				 ->will($this->returnValue($em));

		return $registry;
	}
}