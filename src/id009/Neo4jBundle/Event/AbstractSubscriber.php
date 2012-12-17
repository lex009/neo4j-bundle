<?php
namespace id009\Neo4jBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use HireVoice\Neo4j\EntityManager;

/**
 * Abstract class implements EventSubscriberInterface for Neo4j OGM events
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
abstract class AbstractSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			EntityManager::ENTITY_CREATE   => array('onEntityCreate'),
			EntityManager::RELATION_CREATE => array('onRelationCreate'),
			EntityManager::QUERY_RUN       => array('onQueryRun'),
		);
	}

	abstract public function onEntityCreate($entity);

	abstract public function onRelationCreate($relation, $a, $b, $relationship);

	abstract public function onQueryRun($query, $parameters, $time);
}