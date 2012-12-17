<?php
namespace id009\Neo4jBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use HireVoice\Neo4j\Annotation as OGM;

/**
 * @OGM\Entity
 */
class OGMUser implements UserInterface, \Serializable
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
     * @OGM\Property(format = "array")
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

	public function addRole($role)
	{
		$this->roles[] = $role;
	}

	public function removeRole($role)
	{
		$this->roles = array_diff($this->roles, array($role));
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

	public function serialize()
	{
		return serialize(array(
			'id' => $this->getId(),
			'roles' => $this->getRoles()
		));
	}

	 public function unserialize($data) {
	 	 $data = unserialize($data);
	 	 
	 	 $this->id = $data['id'];
	 	 $this->roles = $data['roles'];
	 }
}