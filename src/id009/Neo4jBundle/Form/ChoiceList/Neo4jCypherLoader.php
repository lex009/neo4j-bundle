<?php
namespace id009\Neo4jBundle\Form\ChoiceList;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use HireVoice\Neo4j\EntityManager;
use HireVoice\Neo4j\Query\Cypher;

/**
 * Class for loading data form Neo4j
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class Neo4jCypherLoader implements EntityLoaderInterface
{
	/**
	 * @var Cypher
	 */
	private $cypher;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	private $class;

    /**
     * @param Cypher|\Closure $cypher
     * @param string $class
     */
	public function __construct($cypher, $class, EntityManager $entityManager)
	{
		if (!($cypher instanceof Cypher || $cypher instanceof \Closure)){
			throw new UnexpectedTypeException($cypher, 'HireVoice\Neo4j\Query\Cypher or \Closure');
		}

		if ($cypher instanceof \Closure){
			$cypher = $cypher($entityManager->createCypherQuery());

			if (!$cypher instanceof Cypher){
				throw new UnexpectedTypeException($cypher, 'HireVoice\Neo4j\Query\Cypher');
			}
		}

		$this->entityManager = $entityManager;

		$this->class = $class;

		$this->cypher = $cypher;
	}

	public function getEntities()
	{
		return array_values($this->cypher
			->getList()
			->toArray()
		);
	}

	public function getEntitiesByIds($identifier = null, array $values)
	{
		$cypher = clone ($this->cypher);

		return array_values ($cypher
			->startWithNodes('entities', $values)
			->end('entities')
			->getList()
			->toArray()
		);
	}
}