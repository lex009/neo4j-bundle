<?php
namespace id009\Neo4jBundle\Form;

use Symfony\Component\Form\AbstractExtension;
use id009\Neo4jBundle\ManagerRegistry;

/**
 * Form extension
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class Neo4jExtension extends AbstractExtension
{
	private $managerRegistry;

	public function __construct(ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	protected function loadTypes()
	{
		return array(new Type\EntityType($this->managerRegistry));
	}

	protected function loadTypeGuesser()
	{
		return new Neo4jTypeGuesser($this->managerRegistry);
	}
}