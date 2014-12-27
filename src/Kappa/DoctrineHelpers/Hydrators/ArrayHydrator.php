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

use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use Nette\Object;

/**
 * Class ArrayHydrator
 *
 * @package Kappa\DoctrineHelpers\Hydrators
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayHydrator extends Object
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
	 * @param array $array
	 * @param object $entity
	 * @param array $ignoreList
	 * @param bool $convertCollections
	 */
	public function hydrate(array &$array, $entity, array $ignoreList = [], $convertCollections = true)
	{
		$entityReflection = $this->entityReflectionFactory->create($entity);
		foreach ($entityReflection->getProperties() as $column) {
			if (!in_array($column, $ignoreList)) {
				$array[$column] = $entityReflection->get($column, $convertCollections);
			}
		}
	}
}
