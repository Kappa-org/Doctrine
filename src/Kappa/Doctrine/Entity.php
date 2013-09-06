<?php
/**
 * This file is part of the Kappa/Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
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