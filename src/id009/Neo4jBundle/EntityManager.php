<?php
namespace id009\Neo4jbundle;

use HireVoice\Neo4j\EntityManager as BaseEntityManager;
use HireVoice\Neo4j\Configuration;

class EntityManager extends BaseEntityManager
{
	public function __construct($host, $port, $proxyDir, $username = null, $password = null)
	{
		parent::__construct(new Configuration(array(
			'host'      => $host,
			'port'      => $port,
			'proxy_dir' => $proxyDir,
			'username'  => $username,
			'password'  => $password,
		)));
	}
}