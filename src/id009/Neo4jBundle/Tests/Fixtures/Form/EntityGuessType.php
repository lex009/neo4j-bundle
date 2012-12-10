<?php
namespace id009\Neo4jBundle\Tests\Fixtures\Form;

use HireVoice\Neo4j\Annotation as OGM;
use Doctrine\Common\Collections\ArrayColletion;

/**
 * @OGM\Entity
 */
 class EntityGuessType
 {
 	/**
 	 * @OGM\Auto
 	 */
 	private $id;

 	/**
 	 * @OGM\ManyToMany
 	 */
 	private $manyToMany;

 	/**
 	 * @OGM\ManyToOne
 	 */
 	private $manyToOne;
 }