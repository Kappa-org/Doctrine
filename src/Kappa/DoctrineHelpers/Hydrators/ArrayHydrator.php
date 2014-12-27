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
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class ArrayHydrator
 *
 * @package Kappa\DoctrineHelpers\Hydrators
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayHydrator extends Object
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityManipulator */
	private $entityManipulator;

	/**
	 * @param EntityManager $entityManager
	 * @param EntityManipulator $entityManipulator
	 */
	public function __construct(EntityManager $entityManager, EntityManipulator $entityManipulator)
	{
		$this->entityManager = $entityManager;
		$this->entityManipulator = $entityManipulator;
	}

	/**
	 * @param array $array
	 * @param object $entity
	 * @param array $ignoreList
	 * @param bool $convertCollections
	 */
	public function hydrate(array &$array, $entity, array $ignoreList = [], $convertCollections = true)
	{
		$columns = $this->getColumns($entity);
		foreach ($columns as $column) {
			if (!in_array($column, $ignoreList)) {
				$array[$column] = $this->entityManipulator->get($entity, $column, $convertCollections);
			}
		}
	}

	/**
	 * @param object $entity
	 * @return array
	 */
	private function getColumns($entity)
	{
		$entityClass = get_class($entity);
		$mapping = $this->entityManager->getClassMetadata($entityClass);

		return array_merge($mapping->getFieldNames(), $mapping->getAssociationNames());
	}
}
