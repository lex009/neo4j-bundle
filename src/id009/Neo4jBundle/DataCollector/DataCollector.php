<?php
namespace id009\Neo4jBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector as BaseDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataCollector extends BaseDataCollector
{
	protected $events;

	public function __construct()
	{
		$this->events = array();
	}

	public function logEvent($event)
	{
		$this->events[] = $event;
	}

	public function collect(Request $request, Response $response, Exception $exception = null)
	{
		return $this->data['events'] = array_map('json_encode', $this->events);
	}

	public function getEvents()
	{	
		$events = array();
		foreach ($this->data['events'] as $event) {
			$events[] = json_decode($event);
		}
		return $events;
	}

	public function getName()
	{
		return 'Neo4j';
	}
}