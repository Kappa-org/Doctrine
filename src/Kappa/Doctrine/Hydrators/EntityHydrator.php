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
use Kdyby\Doctrine\Mapping\ClassMetadata;

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
		$mappings = $this->getCollectionColumnNames($entity);
		foreach ($array as $property => $value) {
			try {
				if (is_array($value) && in_array($property, $mappings)) {
					foreach ($value as $object) {
						$this->entityManipulator->addValue($entity, $property, $object);
					}
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
	private function getCollectionColumnNames($entity)
	{
		$entityName = get_class($entity);
		$metadata = $this->entityManager->getClassMetadata($entityName);
		$associationMappings = $metadata->getAssociationNames();
		foreach ($associationMappings as $index => $asoc)  {
			$mapping = $metadata->getAssociationMapping($asoc);
			if ($mapping['type'] == ClassMetadata::ONE_TO_ONE || $mapping['type'] == ClassMetadata::MANY_TO_ONE) {
				unset($associationMappings[$index]);
			}
		}

		return $associationMappings;
	}
} 