Neo4j Bundle
============
Provides integration [Neo4j PHP Object Graph Mapper](https://github.com/lphuberdeau/Neo4j-PHP-OGM) with Symfony

Installation with Composer
--------------------------
To install this bundle just add following line to "require" section of your composer.json file:
`"id009/neo4jbundle": "dev-master"`

Run `php composer.phar update` command. After updating is finished, register the bundle in your AppKernel.php:

````
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new id009\Neo4jBundle\id009Neo4jBundle(),
    );
    // ...
}
````

Configuration
-------------
To finish bundle installation, you need configuration that sets up entity managers and connections.
Quite full configuration shown below.

````
id009_neo4j:
	connections:
        default:
            host: localhost
            port: 7474
            username: user
            password: pass
    entity_managers:
        default:
            debug: true
            #For pathfinding algorithms refer to Everyman\Neo4j\PathFinder
        	pathfinder_algorithm: allSimplePaths
        	pathfinder_maxdepth: 5
````
But in most common cases you'll need something looks like this:

````
id009_neo4j:
	connections:
        default:
    entity_managers:
        default:
````
And that's all. In configuration above host is localhost and port is 7474 by default. Debug mode is taken from global debug parameter.
Now, after the bundle is installed and configured properly, you can start using it. 

Basics
------
Please, refer to [Neo4j PHP Object Graph Mapper](https://github.com/lphuberdeau/Neo4j-PHP-OGM) readme to understand how OGM works.

If you have a deal with Doctrine ORM Bundle you'll see that basic usage of Neo4j Bundle is pretty straightforward:

````
// Your controller...
$em = $this->get('id009_neo4j.entity_manager');
// Now you have instance of HireVoice\Neo4j\EntityManager class. You can do what ever you want with it.
$em->getRepository('Acme\AcmeBundle\Entity')->findOneByUsername('ivanpetrov99'); // and so on...
````

Security
--------
This bundle provides basic OGMUser class and Security provider, working the same as the entity provider described in the [cookbook](http://symfony.com/doc/current/cookbook/security/entity_provider.html). Basic security configuration shown below.
````
providers:
      neo4j_provider:
          neo4j: {class: id009\Neo4jBundle\Security\User\OGMUser}
````

Forms
-----
The bundle wouldn't be useful enough without integration with Symfony forms. So lets get down to it.

For instance you have a Person and City classes as shown below

````
// Acme\AcmeBundle\Entity\Person

/**
 * @OGM\Entity
 */
class Person
{
    //...

    /**
     * @OGM\ManyToOne
     */
    protected $city;

    //...
}

// Acme\AcmeBundle\Entity\City

/**
 * @OGM\Entity
 */
class City
{
    //...
}
````
It's a good idea to have a form type to select City when editing Person, isn't it? Here is an example:

````
// Acme\AcmeBundle\Form\Type\PersonType

public function buildForm(FormBuilderInterface $builder, array $options)
{
    //...

    $builder->add('city', null, array(
        'class' => 'Acme\AcmeBundle\Entity\City'
    ));

    //...
}
````
Form type will be automatically detected as `neo4j_entity` with options `array('multiple' => false, 'expanded' => false)`, and it will be rendered as dropdown list.

Events
------
OGM provides three types of events:

* Entity create
* Relation create
* Query run

Please refer to [HireVoice\Neo4j\EntityManager](https://github.com/lphuberdeau/Neo4j-PHP-OGM/blob/master/lib/HireVoice/Neo4j/EntityManager.php) for details on these events.

If want to subscribe on these events you can create your own Subscriber class that implements `Symfony\Component\EventDispatcher\EventSubscriberInterface`. But for convenience the bundle has an abstract  class `id009\Neo4jBundle\Event\AbstractSubscriber`, and you can inherit your subscribers from it. For example:

````
namespace Acme\AcmeBundle\Event

//...

class MySubscriber extends AbstractSubscriber
{
    public function onEntityCreate($entity)
    {
        //...
    }

    public function onRelationCreate($relation, $a, $b, $relationship)
    {
        //...
    }

    public function onQueryRun($query, $parameters, $time)
    {
        //...
    }
}
````

Now, after the subscriber is done, register it in your services.yml file:

````
acme_acmebundle.event.subscriber:
    class: 'Acme\AcmeBundle\Event\MySubscriber'
    tags:
        - {name: id009_neo4j.subscriber}
````

If you have several entity manager within your project just add attribute `manager` with entity manager name to the tag.

Contributors
------------
Alex Belyaev [@lex009](https://github.com/lex009/neo4j-bundle)

You are always welcome to contribute!

I would appreciate any feedback.

License
-------
MIT


