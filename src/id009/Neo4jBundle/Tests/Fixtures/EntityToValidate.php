<?php
namespace id009\Neo4jBundle\Tests\Fixtures;

use HireVoice\Neo4j\Annotation as OGM;

/**
 * @OGM\Entity
 */
class EntityToValidate
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

	/**
	 * @OGM\ManyToMany
	 */
	private $many = array();

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

	public function getMany()
	{
		return $this->many;
	}

	public function addMany($many)
	{
		$this->many = $many;
	}
}