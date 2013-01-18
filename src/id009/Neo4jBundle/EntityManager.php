<?php
namespace id009\Neo4jbundle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use HireVoice\Neo4j\EntityManager as BaseEntityManager;
use HireVoice\Neo4j\Configuration;
use HireVoice\Neo4j\Exception;

/**
 * Entity Manager
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class EntityManager extends BaseEntityManager
{
	public function addSubscriber(EventSubscriberInterface $subscriber)
	{
		$events = $subscriber::getSubscribedEvents();
		foreach ($events as $event => $methods){
			if (!in_array($event, array($this::ENTITY_CREATE, $this::RELATION_CREATE, $this::QUERY_RUN))){
				throw new Exception(sprintf('Wrong event name "%s"', $event));
			}

			foreach ($methods as $method){
				$this->registerEvent($event, array($subscriber, $method));
			}
		}
	}
}