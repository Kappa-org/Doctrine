<?php
/**
 * This file is part of the doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class UserEntity extends BaseEntity
{
	use Identifier;

	/**
	 * @ORM\Column(type="string", name="supername")
	 */
	protected $name;

	/**
	 * @ORM\OneToOne(targetEntity="UserEntity")
	 */
	protected $info;

	/**
	 * @ORM\OneToMany(targetEntity="UserEntity", mappedBy="parent")
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(targetEntity="UserEntity")
	 */
	protected $parent;

	/**
	 * @ORM\ManyToMany(targetEntity="UserEntity")
	 */
	protected $users;

	/**
	 * @param $name
	 * @param UserEntity $info
	 * @param UserEntity $children
	 * @param UserEntity $parent
	 * @param UserEntity $users
	 */
	public function __construct($name, UserEntity $info = null, UserEntity  $children = null, UserEntity  $parent = null, UserEntity  $users = null)
	{
		$this->name = $name;
		$this->users = new ArrayCollection();
		$this->children = new ArrayCollection();
		if ($info) {
			$this->setInfo($info);
		}
		if ($children) {
			$this->addChildren($children);
		}
		if ($parent) {
			$this->setParent($parent);
		}
		if ($users) {
			$this->addUser($users);
		}
	}

	public function addChildren(UserEntity $user)
	{
		$this->children->add($user);

		return $this;
	}
}
