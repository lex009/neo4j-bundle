<?php
namespace id009\Neo4jBundle\Tests\Form;

use id009\Neo4jBundle\Tests\TestCase;
use id009\Neo4jBundle\Tests\Fixtures\Form\EntityGuessType;
use id009\Neo4jBundle\Form\Neo4jTypeGuesser;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\Guess;

class Neo4jTypeGuesserTest extends TestCase
{
	public function testGuessType()
	{
		$entity = new EntityGuessType();

		$typeGuesser = new Neo4jTypeGuesser($this::getEntityManager());
		
		$guessedType1 = $typeGuesser->guessType('id009\Neo4jBundle\Tests\Fixtures\Form\EntityGuessType', 'manyToMany');
		$guessedType2 = $typeGuesser->guessType('id009\Neo4jBundle\Tests\Fixtures\Form\EntityGuessType', 'manyToOne');
		
		$type1 = new TypeGuess('neo4j_entity', array(
			'multiple' => true,
			'expanded' => true,
		), Guess::HIGH_CONFIDENCE);

		$type2 = new TypeGuess('neo4j_entity', array(
			'multiple' => false,
			'expanded' => false,
		), Guess::HIGH_CONFIDENCE);

		$this->assertEquals($guessedType1, $type1);
		$this->assertEquals($guessedType2, $type2);
	}
}