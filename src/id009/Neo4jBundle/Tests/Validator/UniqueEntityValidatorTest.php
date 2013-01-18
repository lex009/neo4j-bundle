<?php
namespace id009\Neo4jBundle\Tests\Validator;

use id009\Neo4jBundle\Tests\TestCase;
use id009\Neo4jBundle\Tests\Fixtures\EntityToValidate as Entity;
use id009\Neo4jBundle\Validator\UniqueEntityValidator;
use id009\Neo4jBundle\Validator\Constraints\Unique;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator;

class UniqueEntityValidatorTest extends TestCase
{
	protected $em;

	protected $registry;

	public function setUp(){

		$em = $this::getEntityManager();

		$this->em = $em;
		
		$this->registry = $this->createRegistry($em, 'default');
		
		parent::setUp();
	}

	public function testValidateUniqueness()
	{
		$entity1 = $this->em->getRepository('id009\Neo4jBundle\Tests\Fixtures\Form\Entity')->findOneByName('Alexsey');
		if (!$entity1){
			$entity1 = new Entity();
			$entity1->setName('Alexsey');

			$this->em->persist($entity1);
			$this->em->flush();
		}

		$entity2 = new Entity();
		$entity2->setName('Alexsey');

		$validator = $this->createValidator($this->registry, array('name'));
		$violationsList = $validator->validate($entity2);

		$this->assertEquals(1, $violationsList->count());

		$entity3 = new Entity();
		$entity3->setName('Fyodor');

		$violationsList = $validator->validate($entity3);

		$this->assertEquals(0, $violationsList->count());
	}

	/**
     * @expectedException Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
	public function testManyToManyException()
	{
		$validator = $this->createValidator($this->registry, array('many'));

		$entity = new Entity();

		$validator->validate($entity);
	}

	private function createMetadataFactoryMock($metadata)
    {
        $metadataFactory = $this->getMock('Symfony\Component\Validator\Mapping\ClassMetadataFactoryInterface');
        $metadataFactory->expects($this->any())
                        ->method('getClassMetadata')
                        ->with($this->equalTo($metadata->name))
                        ->will($this->returnValue($metadata));

        return $metadataFactory;
    }

    private function createValidatorFactory($uniqueValidator)
    {
        $validatorFactory = $this->getMock('Symfony\Component\Validator\ConstraintValidatorFactoryInterface');
        $validatorFactory->expects($this->any())
                         ->method('getInstance')
                         ->with($this->isInstanceOf('Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity'))
                         ->will($this->returnValue($uniqueValidator));

        return $validatorFactory;
    }

    private function createValidator($registry, $fields)
    {
    	$validator = new UniqueEntityValidator($this->registry);

    	$metadata = new ClassMetadata('id009\Neo4jBundle\Tests\Fixtures\EntityToValidate');
    	$constraint = new Unique(array(
			'fields' => $fields,
			'em' => 'default',
		));
		$metadata->addConstraint($constraint);

		$metadataFactory = $this->createMetadataFactoryMock($metadata);
        $validatorFactory = $this->createValidatorFactory($validator);

        return new Validator($metadataFactory, $validatorFactory);
    }
}