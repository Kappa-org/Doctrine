<?php
/**
 * Entity.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 30.8.12
 *
 * @package Kappa
 */

namespace Kappa\Doctrine;

use Nette\Object;

/** @MappedSuperClass */
class Entity extends Object
{
	/**
	 * @id
	 * @column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return get_object_vars($this);
	}
}
