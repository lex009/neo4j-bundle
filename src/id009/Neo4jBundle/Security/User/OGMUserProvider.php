<?php
namespace id009\Neo4jBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use id009\Neo4jBundle\ManagerRegistry;

/**
 * User provider for OGM entities
 *
 * @author Alex Belyaev <lex@alexbelyaev.com>
 */
class OGMUserProvider implements UserProviderInterface
{
	private $entityManager;

	private $class;

	private $property;

	private $repository;

	public function __construct(ManagerRegistry $managerRegistry, $class, $property = null, $manager = null)
	{
		$this->entityManager = $managerRegistry->getManager($manager);

		$this->class = $class;

		$this->property = $property;

		$this->repository = $this->entityManager->getRepository($class);
	}

	public function loadUserByUsername($username)
	{
		if (null !== $this->property){
			$user = $this->repository->findOneBy(array($this->property => $username));
		} else {
			$user = $this->repository->findOneByUsername($username);
		}

		if ($user) return $user;

		throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
	}

	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof $this->class) 
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));

        if (!$id = $user->getId())
        	throw new \InvalidArgumentException('You cannot refresh user that does not contain an identifier');

        if (null === $refreshedUser = $this->repository->find($id))
        	throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));

        return $refreshedUser;
	}

	public function supportsClass($class)
	{
		return $class === $this->class || is_subclass_of($class, $this->class);
	}
}