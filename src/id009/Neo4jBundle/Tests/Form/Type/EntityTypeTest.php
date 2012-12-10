<?php
namespace id009\Neo4jBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use id009\Neo4jBundle\Tests\TestCase;
use id009\Neo4jBundle\Tests\Fixtures\Form\Entity;
use id009\Neo4jBundle\Form\Type\EntityType;
use id009\Neo4jBundle\Form\Neo4jExtension;

class EntityTypeTest extends TypeTestCase
{
	protected $entityManager;

	public function setUp()
	{
		$this->entityManager = TestCase::getEntityManager();

		parent::setUp();
	}

	protected function getEntities(array $entities)
	{
		foreach ($entities as &$entity){
			$e = $this->entityManager->getRepository('id009\Neo4jBundle\Tests\Fixtures\Form\Entity')->findOneByName($entity->getName());
			if 
				(!$e) $this->entityManager->persist($entity);
			else
				$entity = $e;
		}

		$this->entityManager->flush();

	}

	public function testLoadDataWithoutCypher()
	{
		$entity1 = new Entity();
		$entity1->setName('Ivan');

		$entity2 = new Entity();
		$entity2->setName('Sergey');

		$entityType = new EntityType($this->entityManager);

		$this->getEntities(array(&$entity1, &$entity2));

		$field = $this->factory->createNamed('name', 'neo4j_entity', null, array(
			'class' => 'id009\Neo4jBundle\Tests\Fixtures\Form\Entity',
		));

		$this->assertEquals(array(
			$entity1->getId() => new ChoiceView($entity1, $entity1->getId(), 'Ivan'),
			$entity2->getId() => new ChoiceView($entity2, $entity2->getId(), 'Sergey'),
		), $field->createView()->vars['choices']);
	}

	public function testLoadDataWithCypher()
	{
		$entity1 = new Entity();
		$entity1->setName('Ivan');

		$entity2 = new Entity();
		$entity2->setName('Sergey');

		$this->getEntities(array(&$entity1, &$entity2));

		$field = $this->factory->createNamed('name', 'neo4j_entity', null, array(
			'class' => 'id009\Neo4jBundle\Tests\Fixtures\Form\Entity',
			'cypher' => function ($cypher){
				$cypher
				   ->startWithQuery('entities', 'id009\Neo4jBundle\Tests\Fixtures\Form\Entity', 'id:*')
				   ->where('entities.name = "Sergey"')
				   ->end('entities');

				return $cypher;
			}
		));

		$this->assertEquals(array(
			$entity2->getId() => new ChoiceView($entity2, $entity2->getId(), 'Sergey')
		), $field->createView()->vars['choices']);
	}

	public function testMultiple()
	{
		$entity1 = new Entity();
		$entity1->setName('Ivan');

		$entity2 = new Entity();
		$entity2->setName('Sergey');

		$this->getEntities(array(&$entity1, &$entity2));

		$field = $this->factory->createNamed('name', 'neo4j_entity', null, array(
			'class' => 'id009\Neo4jBundle\Tests\Fixtures\Form\Entity',
			'multiple' => true
		));

		$this->assertEquals(array(
			$entity1->getId() => new ChoiceView($entity1, $entity1->getId(), 'Ivan'),
			$entity2->getId() => new ChoiceView($entity2, $entity2->getId(), 'Sergey'),
		), $field->createView()->vars['choices']);
	}

	protected function getExtensions()
	{
		return array_merge(parent::getExtensions(), array(
            new Neo4jExtension($this->entityManager),
        ));
	}
}