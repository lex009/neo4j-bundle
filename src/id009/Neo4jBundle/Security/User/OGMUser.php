<?php
namespace id009\Neo4jBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use HireVoice\Neo4j\Annotation as OGM;

/**
 * @OGM\Entity
 */
class OGMUser implements UserInterface
{
	/**
     * @OGM\Auto
     */
	private $id;

	/**
     * @OGM\Property
     * @OGM\Index
     */
	private $username;

	/**
     * @OGM\Property
     */
	private $password;

	/**
     * @OGM\Property
     */
	private $salt;

	/**
     * @OGM\Property
     */
	private $roles;

	public function __construct($username = null, $password = null, $salt = null, array $roles = array())
	{
		$this->username = $username;
		$this->password = $password;
		$this->salt = $salt;
		$this->roles = $roles;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		return $this->id;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function setRoles(array $roles)
	{
		$this->roles = $roles;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getSalt()
	{
		return $this->salt;
	}

	public function setSalt($salt)
	{
		$this->salt = $salt;
	}

	public function eraseCredentials()
	{

	}

	public function equals(UserInterface $user)
	{
		if (!$user instanceof User) return false;

		if ($this->password !== $user->getPassword()) return false;

		if ($this->salt !== $user->getSalt()) return false;

		if ($this->username != $user->getUsername()) return false;

		return true;
	}
}