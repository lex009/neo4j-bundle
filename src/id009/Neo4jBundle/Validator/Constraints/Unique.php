<?php
namespace id009\Neo4jBundle\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Constraint for the unique entity validator
 *
 * @Annotation
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class Unique extends UniqueEntity
{
	public $service = 'id009_neo4j.unique';
}