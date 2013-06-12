<?php
/**
 * Entity.php
 * 
 * @author: Ondřej Záruba <zarubaondra@gmail.com>
 * @data 12.6.13
 *
 * @package kappa-doctrine
 */

namespace Kappa\Doctrine;

use Kdyby\Doctrine\Entities\IdentifiedEntity;

/**
 * Class Entity
 * @package Kappa\Doctrine
 */
abstract class Entity extends IdentifiedEntity
{
	/**
	 * @return array
	 */
	public function __toArray()
	{
		return array_merge(array('id' => $this->getId()), get_object_vars($this));
	}
}