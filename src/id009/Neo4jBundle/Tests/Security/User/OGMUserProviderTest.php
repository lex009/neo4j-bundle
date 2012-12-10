<?php
namespace id009\Neo4jBundle\Tests\Security\User;

use id009\Neo4jBundle\Tests\TestCase;
use id009\Neo4jBundle\Security\User\OGMUser;
use id009\Neo4jBundle\Security\User\OGMUserProvider;

class OGMUserProviderTest extends TestCase
{
	public function testLoadUserByUsername()
	{
		$em = $this::getEntityManager();

		$user = $this->getUser($em);

		$provider = new OGMUserProvider($em, 'id009\Neo4jBundle\Security\User\OGMUser', 'username');
		
		$loadedUser = $provider->loadUserByUsername('testuser');

		$this->assertSame($user, $loadedUser);
	}

	public function testRefreshUser()
	{
		$em = $this::getEntityManager();

		$user = $this->getUser($em);

		$user->setUsername('testuser1');

		$provider = new OGMUserProvider($em, 'id009\Neo4jBundle\Security\User\OGMUser', 'username');
		
		$refreshedUser = $provider->refreshUser($user);

		$this->assertSame($user, $refreshedUser);
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testRefreshUserWithoutId()
	{
		$em = $this::getEntityManager();

		$user = new OGMUser('testuser', 'testpass');

		$provider = new OGMUserProvider($em, 'id009\Neo4jBundle\Security\User\OGMUser', 'username');
		
		$refreshedUser = $provider->refreshUser($user);
	}

	private function getUser($em)
	{
		$user = $em->getRepository('id009\Neo4jBundle\Security\User\OGMUser')->findOneByUsername('testuser');

		if (!$user){
			$salt = md5(uniqid(null, true));
			
			$user = new OGMUser('testuser', 'testpass', $salt, array('ROLE_USER'));

			$em->persist($user);
			$em->flush();
		}

		return $user;
	}
}