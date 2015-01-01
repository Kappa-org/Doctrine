<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\Converters;

use Kappa\DoctrineHelpers\Reflections\EntityReflection;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use Nette\Object;

/**
 * Class EntityArrayConverter
 *
 * @package Kappa\DoctrineHelpers\Converters
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityArrayConverter extends Object
{
	/** @var EntityReflection */
	private $entityReflectionFactory;

	/**
	 * @param EntityReflectionFactory $entityReflectionFactory
	 */
	public function __construct(EntityReflectionFactory $entityReflectionFactory)
	{
		$this->entityReflectionFactory = $entityReflectionFactory;
	}

	/**
	 * @param object $entity
	 * @param array $values
	 * @param array $ignoreList
	 * @return object
	 */
	public function arrayToEntity($entity, array $values, array $ignoreList = [])
	{
		$entityReflection = $this->entityReflectionFactory->create($entity);
		foreach ($values as $column => $value) {
			if (!in_array($column, $ignoreList) && in_array($column, $entityReflection->getProperties())) {
				$type = $entityReflection->getSetterType($column);
				$entityReflection->invoke($column, $values[$column], $type);
			}
		}

		return $entityReflection->getEntity();
	}

	/**
	 * @param object $entity
	 * @param array $ignoreList
	 * @param bool $convertCollections
	 * @param array $transformEntity
	 * @return array
	 */
	public function entityToArray($entity, array $ignoreList = [], $convertCollections = true, array $transformEntity = null)
	{
		$entityReflection = $this->entityReflectionFactory->create($entity);
		$array = [];
		foreach ($entityReflection->getProperties() as $column) {
			if (!in_array($column, $ignoreList)) {
				$array[$column] = $entityReflection->get($column, $convertCollections, $transformEntity);
			}
		}

		return $array;
	}
}
