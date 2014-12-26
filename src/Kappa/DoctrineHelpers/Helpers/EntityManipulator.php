<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\Helpers;

use Doctrine\Common\Collections\Collection;
use Nette\Object;
use Nette\Utils\Callback;

/**
 * Class EntityManipulator
 *
 * @package Kappa\DoctrineHelpers\Helpers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityManipulator extends Object
{
	const SET_TYPE = "set";

	const ADD_TYPE = "add";

	const GET_TYPE = "get";

	/**
	 * @param object $entity
	 * @param string $column
	 * @param mixed $value
	 * @param string $type
	 */
	public function invoke($entity, $column, $value, $type)
	{
		$ref = new \ReflectionProperty($entity, $column);
		if ($ref->isPublic()) {
			if ($ref->getValue($entity) instanceof Collection) {
				$ref->getValue($entity)->add($value);
			} else {
				$ref->setValue($entity, $value);
			}
		} else {
			Callback::invokeArgs([$entity, $this->getMethodName($type, $column)], [$value]);
		}
	}

	/**
	 * @param object $entity
	 * @param string $column
	 * @return mixed
	 */
	public function get($entity, $column)
	{
		$ref = new \ReflectionProperty($entity, $column);
		if ($ref->isPublic()) {
			if ($ref->getValue($entity) instanceof Collection) {
				return $ref->getValue($entity)->toArray();
			} else {
				return $ref->getValue($entity);
			}
		} else {
			return Callback::invoke([$entity, $this->getMethodName(self::GET_TYPE, $column)]);
		}
	}

	/**
	 * @param string $type
	 * @param string $column
	 * @return string
	 */
	private function getMethodName($type, $column)
	{
		$name = $type;
		if ($type == self::ADD_TYPE) {
			if (substr($column, -3) == 'ies') {
				$column = substr($column, 0, strlen($column) - 3) . 'y';
			} elseif (substr($column, -1) == 's') {
				$column = substr($column, 0, strlen($column) - 1);
			}
		}
		$name .= ucfirst($column);

		return $name;
	}
}
