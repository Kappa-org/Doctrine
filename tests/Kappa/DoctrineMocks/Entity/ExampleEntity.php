<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Tests\DoctrineMocks\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tests")
 */
class ExampleEntity extends BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/**
	 * @ORM\OneToMany(targetEntity="ExampleEntity2", mappedBy="parent")
	 */
	protected $entities;


	public function __construct()
	{
		$this->entities = new ArrayCollection();
	}

	/*public function addEntity($e)
	{
		$this->entities[] = $e;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getEntities()
	{
		return $this->entities;
	}*/
}