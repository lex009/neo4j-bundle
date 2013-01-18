<?php
namespace id009\Neo4jBundle\Form\Type;

use id009\Neo4jBundle\Form\ChoiceList\Neo4jCypherLoader;
use id009\Neo4jBundle\Form\ChoiceList\ChoiceList;
use id009\Neo4jBundle\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Neo4j Entity form type
 *  
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class EntityType extends AbstractType
{
	private $choiceListCache = array();

	private $managerRegistry;

	public function __construct(ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		if ($options['multiple']){
			$builder->addViewTransformer(new CollectionToArrayTransformer(), true);
		}
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$choiceListCache =& $this->choiceListCache;

        $that = $this;

        $managerRegistry = $this->managerRegistry;

		$loader = function(Options $options){
			if (null !== $options['cypher'])
				return new Neo4jCypherLoader($options['cypher'], $options['class'], $options['em']);

			return null;
		};

		$choiceList = function(Options $options) use (&$choiceListCache, &$time){
			$propertyHash = is_object($options['property']) ? spl_object_hash($options['property']) : $options['property'];

			$choiceHashes = $options['choices'];

			if (is_array($choiceHashes)) {
                array_walk_recursive($choiceHashes, function ($value) {
                    return spl_object_hash($value);
                });
            }

            $preferredChoiceHashes = $options['preferred_choices'];

            if (is_array($preferredChoiceHashes)) {
                array_walk_recursive($preferredChoiceHashes, function ($value) {
                    return spl_object_hash($value);
                });
            }

            $loaderHash = is_object($options['loader']) ? spl_object_hash($options['loader']) : $options['loader'];

            $groupByHash = is_object($options['group_by']) ? spl_object_hash($options['group_by']) : $options['group_by'];

            $hash = md5(json_encode(array(
                spl_object_hash($options['em']),
                $options['class'],
                $propertyHash,
                $loaderHash,
                $choiceHashes,
                $preferredChoiceHashes,
                $groupByHash
            )));

            if (!isset($choiceListCache[$hash])){
            	$choiceListCache[$hash] = new ChoiceList(
            		$options['em'],
            		$options['class'],
            		$options['property'],
                    $options['loader'],
                    $options['choices'],
                    $options['preferred_choices'],
                    $options['group_by']
            	);
            }

            return $choiceListCache[$hash];
		};


		$resolver->setDefaults(array(
            'em'             => null,
			'property'       => null,
			'cypher'         => null,
			'loader'         => $loader,
			'choices'        => null,
            'choice_list'    => $choiceList,
            'group_by'       => null,
		));

        $registry = $this->managerRegistry;

        $emNormalizer = function (Options $options, $em) use ($managerRegistry){
            return $managerRegistry->getManager($em);
        };

		$resolver->setRequired(array('class'));

        $resolver->setNormalizers(array(
            'em' => $emNormalizer,
        ));
	}

	public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
    	return 'neo4j_entity';
    }
}