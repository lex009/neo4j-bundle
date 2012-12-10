<?php
namespace id009\Neo4jBundle\Tests;

use id009\Neo4jBundle\EntityManager;

class TestCase extends \PHPUnit_Framework_TestCase
{
	public static function getEntityManager()
	{
		return new EntityManager($GLOBALS['host'], $GLOBALS['port'], \sys_get_temp_dir());
	}
}