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
	public function testValidateUniqueness()
	{
		$em = $this::getEntityManager();
		$entity1 = $em->getRepository('id009\Neo4jBundle\Tests\Fixtures\Form\Entity')->findOneByName('Alexsey');
		if (!$entity1){
			$entity1 = new Entity();
			$entity1->setName('Alexsey');

			$em->persist($entity1);
			$em->flush();
		}

		$entity2 = new Entity();
		$entity2->setName('Alexsey');

		$validator = $this->createValidator($em, array('name'));
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
		$em = $this::getEntityManager();
		$validator = $this->createValidator($em, array('many'));

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

    private function createValidator($em, $fields)
    {
    	$validator = new UniqueEntityValidator($em);

    	$metadata = new ClassMetadata('id009\Neo4jBundle\Tests\Fixtures\EntityToValidate');
    	$constraint = new Unique(array(
			'fields' => $fields
		));
		$metadata->addConstraint($constraint);

		$metadataFactory = $this->createMetadataFactoryMock($metadata);
        $validatorFactory = $this->createValidatorFactory($validator);

        return new Validator($metadataFactory, $validatorFactory);
    }
}