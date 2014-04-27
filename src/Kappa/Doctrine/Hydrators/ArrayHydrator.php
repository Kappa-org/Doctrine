<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Hydrators;

use Doctrine\ORM\EntityManager;
use Kappa\Doctrine\Helpers\EntityManipulator;
use Kappa\Doctrine\ReflectionException;

/**
 * Class ArrayHydrator
 * @package Kappa\Doctrine\Hydrators
 */
class ArrayHydrator
{
	/** @var \Doctrine\ORM\EntityManager */
	private $entityManager;

	/** @var \Kappa\Doctrine\Helpers\EntityManipulator */
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
	 * @param array $ignore
	 */
	public function hydrate(array &$array, $entity, array $ignore = array())
	{
		$properties = $this->getEntityProperties($entity);
		foreach ($properties as $column) {
			if (!in_array($column, $ignore)) {
				try {
					$array[$column] = $this->entityManipulator->getValue($entity, $column);
				} catch (ReflectionException $e) {
					continue;
				}
			}
		}
	}

	/**
	 * @param object $entity
	 * @return array
	 */
	private function getEntityProperties($entity)
	{
		$entityName = get_class($entity);
		$mappings = array();
		$associationMappings = $this->entityManager->getClassMetadata($entityName)->getAssociationMappings();
		foreach ($associationMappings as $x => $y) {
			$mappings[] = $x;
		}

		return array_merge($this->entityManager->getClassMetadata($entityName)->getColumnNames(), $mappings);
	}
}