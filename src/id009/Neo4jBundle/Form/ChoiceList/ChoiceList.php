<?php
namespace id009\Neo4jBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use HireVoice\Neo4j\EntityManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;

/**
 * A choice list presenting Neo4j entities as choices
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class ChoiceList extends ObjectChoiceList
{
	/**
	 * @var HireVoice\Neo4j\EntityManager
	 */
	private $entityManager;

	/**
	 * @var EntityLoaderInterface
	 */
	private $entityLoader;

	private $class;

	private $loaded;

	private $preferredEntities;

	public function __construct(EntityManager $entityManager, $class, $labelPath = null, EntityLoaderInterface $loader = null, $entities = null, array $preferredEntities = array(), $groupPath = null)
	{
		$this->entityManager = $entityManager;
		$this->entityLoader = $loader;
		$this->class = $class;
		$this->preferredEntities = $preferredEntities;
		$this->loaded = is_array($entities) || $entities instanceof \Traversable;

		if (!$this->loaded) $entities = array();

		parent::__construct($entities, $labelPath, $preferredEntities, $groupPath);
	}

	public function getLoaded()
	{
		return $this->loaded;
	}

	public function getChoices()
	{
		if (!$this->loaded) $this->load();

		return parent::getChoices();
	}

	public function getValues()
	{
		if (!$this->loaded) $this->load();

		return parent::getValues();
 	}

 	public function getPreferredViews()
 	{
 		if (!$this->loaded) $this->load();

 		return parent::getPreferredViews();
 	}

 	public function getRemainingViews()
 	{
 		if (!$this->loaded) $this->load();

 		return parent::getRemainingViews();
 	}

 	public function getChoicesForValues(array $values)
 	{
 		if (!$this->loaded){
 			if ($this->entityLoader)
 				return $this->entityLoader->getEntitiesByIds(null, $values);

 			$this->load();
 		}

 		return parent::getChoicesForValues($values);
 	}

 	public function getValuesForChoices(array $entities)
 	{
 		if (!$this->loaded){
 			$values = array();

 			foreach ($entities as $entity){
 				if ($entity instanceof $this->class)
 					$values[] = $this->fixValue($entity->getId());
 			}

 			return $values;

 			$this->load();
 		}

 		return parent::getValuesForChoices($entities);
 	}

 	public function getIndicesForChoices(array $entities)
 	{
 		if (!$this->loaded){
 			$values = array();

 			foreach ($entities as $entity){
 				if ($entity instanceof $this->class)
 					$values[] = $this->fixIndex($entity->getId());
 			}

 			return $values;

 			$this->load();
 		}

 		return parent::getIndicesForChoices($entities);
 	}

 	public function getIndicesForValues(array $values)
 	{
 		if (!$this->loaded){
 			return $this->fixIndices($values);
 			$this->load();
 		}

 		return parent::getIndicesForValues($values);
 	}

 	protected function createIndex($entity)
 	{
 		return $entity->getId();
 	}

 	protected function createValue($entity)
 	{
 		return (string) $entity->getId();
 	}

	private function load()
	{
		if ($this->entityLoader)
			$entities = $this->entityLoader->getEntities();
		else 
			$entities = $this->entityManager->createCypherQuery()
				->startWithQuery('entities', $this->class, 'id:*')
    			->end('entities')
    			->getList();

		parent::initialize($entities, array(), $this->preferredEntities);

		$this->loaded = true;
	}
}