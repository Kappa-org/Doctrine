<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Helpers;

use Kappa\Doctrine\ReflectionException;
use Kdyby\Doctrine\MemberAccessException;

/**
 * Class EntityManipulator
 * @package Kappa\Doctrine\Helpers
 */
class EntityManipulator
{
	/**
	 * @param object $entity
	 * @param mixed $columnName
	 * @return mixed
	 * @throws \Kappa\Doctrine\ReflectionException
	 */
	public function getValue($entity, $columnName)
	{
		$method = $this->getMethodName('get', $columnName);
		try {
			return $entity->$method();
		} catch (MemberAccessException $e) {
			throw new ReflectionException($e);
		}
	}

	/**
	 * @param object $entity
	 * @param string $columnName
	 * @param mixed $value
	 * @return mixed
	 * @throws \Kappa\Doctrine\ReflectionException
	 */
	public function setValue($entity, $columnName, $value)
	{
		$method = $this->getMethodName('set', $columnName);
		try {
			return $entity->$method($value);
		} catch (MemberAccessException $e) {
			throw new ReflectionException($e);
		}
	}

	/**
	 * @param object $entity
	 * @param string $property
	 * @param mixed $value
	 * @return mixed
	 * @throws \Kappa\Doctrine\ReflectionException
	 */
	public function addValue($entity, $property, $value)
	{
		$method = $this->getMethodName('add', $property);
		try {
			return $entity->$method($value);
		} catch (MemberAccessException $e) {
			throw new ReflectionException($e);
		}
	}

	/**
	 * @param string $prefix
	 * @param string $name
	 * @return string
	 */
	private function getMethodName($prefix, $name)
	{
		$collection = array('add');
		$methodName = $prefix;
		if (in_array($prefix, $collection)) {
			if (substr($name, -3) == 'ies') {
				$name = substr($name, 0, strlen($name) - 3) . 'y';
			} else {
				$name = substr($name, 0, strlen($name) - 1);
			}
		}
		$methodName .= ucfirst($name);

		return $methodName;
	}
} 