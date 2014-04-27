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

use Kappa\Doctrine\Helpers\EntityManipulator;
use Kappa\Doctrine\ReflectionException;
use Kdyby\Doctrine\EntityManager;

/**
 * Class EntityHydrator
 * @package Kappa\Doctrine\Hydrators
 */
class EntityHydrator 
{
	/** @var \Kappa\Doctrine\Helpers\EntityManipulator */
	private $entityManipulator;

	/** @var \Kdyby\Doctrine\EntityManager */
	private $entityManager;

	/**
	 * @param EntityManager $entityManager
	 * @param EntityManipulator $entityManipulator
	 */
	public function __construct(EntityManager $entityManager, EntityManipulator $entityManipulator)
	{
		$this->entityManipulator = $entityManipulator;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param object $entity
	 * @param array $array
	 */
	public function hydrate($entity, array $array)
	{
		$mappings = $this->getEntityProperties($entity);
		foreach ($array as $property => $value) {
			try {
				if (in_array($property, $mappings)) {
					$this->entityManipulator->addValue($entity, $property, $value);
				} else {
					$this->entityManipulator->setValue($entity, $property, $value);
				}
			} catch (ReflectionException $e) {
				continue;
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

		return $mappings;
	}
} 