<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\Hydrators;

use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use Nette\Object;

/**
 * Class EntityHydrator
 *
 * @package Kappa\DoctrineHelpers\Hydrators
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityHydrator extends Object
{
	/** @var EntityReflectionFactory */
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
	 */
	public function hydrate($entity, array $values, array $ignoreList = [])
	{
		$entityReflection = $this->entityReflectionFactory->create($entity);
		foreach ($values as $column => $value) {
			if (!in_array($column, $ignoreList) && in_array($column, $entityReflection->getProperties())) {
				$type = $entityReflection->getSetterType($column);
				$entityReflection->invoke($column, $values[$column], $type);
			}
		}
	}
}
