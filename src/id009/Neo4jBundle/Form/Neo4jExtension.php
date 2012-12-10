<?php
namespace id009\Neo4jBundle\Form;

use Symfony\Component\Form\AbstractExtension;
use HireVoice\Neo4j\EntityManager;

/**
 * Form extension
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class Neo4jExtension extends AbstractExtension
{
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	protected function loadTypes()
	{
		return array(new Type\EntityType($this->entityManager));
	}

	protected function loadTypeGuesser()
	{
		return new Neo4jTypeGuesser($this->entityManager);
	}
}