<?php
namespace id009\Neo4jBundle\Tests\Fixtures\Form;

use HireVoice\Neo4j\Annotation as OGM;

/**
 * @OGM\Entity
 */
class Entity
{
	/**
	 * @OGM\Auto
	 */
	private $id;

	/**
	 * @OGM\Property
	 * @OGM\Index
	 */
	private $name;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function __toString()
	{
		return $this->name;
	}
}