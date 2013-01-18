<?php
namespace id009\Neo4jBundle\Event;

use id009\Neo4jBundle\DataCollector\DataCollector;
use HireVoice\Neo4j\EntityManager;

class DataCollectorSubscriber extends AbstractSubscriber
{
	protected $dataCollector;
	
	public function setCollector(DataCollector $dataCollector)
	{
		$this->dataCollector = $dataCollector;
	}

	public function onEntityCreate($entity)
	{
		$this->dataCollector->logEvent(array(
			'name' => EntityManager::ENTITY_CREATE,
		));
	}

	public function onRelationCreate($relation, $a, $b, $relationship)
	{
		$this->dataCollector->logEvent(array(
			'name' => EntityManager::RELATION_CREATE
		));
	}

	public function onQueryRun($query, $parameters, $time)
	{
		$this->dataCollector->logEvent(array(
			'name' => EntityManager::QUERY_RUN,
			'time' => $time,
			'query' => $query->getQuery(),
			'parameters' => $parameters,
		));
	}
}