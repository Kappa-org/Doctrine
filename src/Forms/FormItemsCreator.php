<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Forms;

use Kappa\Doctrine\Reflections\EntityReflectionFactory;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\QueryObject;
use Nette\Object;

/**
 * Class FormItemsCreator
 *
 * @package Kappa\Doctrine\Forms
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class FormItemsCreator extends Object
{
	/** @var array */
	private $defaultColumns;

	/** @var EntityManager */
	private $entityManager;

	/**
	 * @param EntityManager $entityManager
	 * @param array $defaultColumns
	 */
	public function __construct(EntityManager $entityManager, array $defaultColumns)
	{
		$this->defaultColumns = $defaultColumns;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param string|object $entity
	 * @param QueryObject $queryObject
	 * @param string|null $identifierColumn
	 * @param string|null $valueColumn
	 * @return array
	 */
	public function create($entity, QueryObject $queryObject, $valueColumn = null, $identifierColumn = null)
	{
		if (is_object($entity)) {
			$entity = get_class($entity);
		}
		$dao = $this->entityManager->getDao($entity);
		$items = [];
		foreach ($dao->fetch($queryObject) as $item) {
			if ($identifierColumn === null) {
				$identifierColumn = $this->defaultColumns['identifierColumn'];
			}
			if ($valueColumn === null) {
				$valueColumn = $this->defaultColumns['valueColumn'];
			}
			$metadata = $this->entityManager->getClassMetadata(get_class($item));
			$id = $metadata->getFieldValue($item, $identifierColumn);
			$value = $metadata->getFieldValue($item, $valueColumn);
			$items[$id] = $value;
		}

		return $items;
	}
}
