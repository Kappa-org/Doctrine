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

use Doctrine\ORM\Mapping\MappingException;
use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\Mapping\ClassMetadata;
use Nette\Object;

/**
 * Class EntityHydrator
 *
 * @package Kappa\DoctrineHelpers\Hydrators
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityHydrator extends Object
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
	 * @param object $entity
	 * @param array $values
	 * @param array $ignoreList
	 */
	public function hydrate($entity, array $values, array $ignoreList = [])
	{
		$columns = $this->getColumns($entity);
		foreach ($columns as $column) {
			if (!in_array($column, $ignoreList) && array_key_exists($column, $values)) {
				$type = $this->getSetterType($entity, $column);
				$this->entityManipulator->invoke($entity, $column, $values[$column], $type);
			}
		}
	}

	/**
	 * @param object $entity
	 * @return array
	 */
	private function getColumns($entity)
	{
		$entityName = get_class($entity);
		$metadata = $this->entityManager->getClassMetadata($entityName);
		$columns = array_merge($metadata->getFieldNames(), $metadata->getAssociationNames());

		return $columns;
	}

	/**
	 * @param $entity
	 * @param $columnName
	 * @return string
	 */
	private function getSetterType($entity, $columnName)
	{
		$entityName = get_class($entity);
		$metadata = $this->entityManager->getClassMetadata($entityName);
		try {
			$assoc = $metadata->getAssociationMapping($columnName);
			if ($assoc['type'] == ClassMetadata::ONE_TO_ONE || $assoc['type'] == ClassMetadata::MANY_TO_ONE) {
				return EntityManipulator::SET_TYPE;
			} else {
				return EntityManipulator::ADD_TYPE;
			}
		} catch (MappingException $e) {
			return EntityManipulator::SET_TYPE;
		}
	}
}
