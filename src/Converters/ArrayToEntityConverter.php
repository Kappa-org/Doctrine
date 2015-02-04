<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Converters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class ArrayToEntityConverter
 *
 * @package Kappa\Doctrine\Converters
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayToEntityConverter extends Object
{
	/** @var EntityManager */
	private $entityManager;

	/** @var object */
	private $entity;

	/** @var string */
	private $class;

	/** @var ClassMetadata */
	private $metadata;

	/** @var array */
	private $data;

	/** @var null|array */
	private $ignoreList;

	/** @var null|array */
	private $whiteList;

	/** @var array */
	private $itemCallbacks = [];

	/**
	 * @param string|object $entity
	 * @param array $data
	 * @param EntityManager $entityManager
	 */
	public function __construct($entity, array $data, EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->data = $data;
		if (is_object($entity)) {
			$this->entity = $entity;
			$this->class = get_class($this->entity);
		} else {
			$this->class = $entity;
		}
	}

	/**
	 * @param array $ignoreList
	 * @return $this
	 */
	public function setIgnoreList(array $ignoreList)
	{
		$this->ignoreList = $ignoreList;

		return $this;
	}

	/**
	 * @param array $whiteList
	 * @return $this
	 */
	public function setWhiteList(array $whiteList)
	{
		$this->whiteList = $whiteList;

		return $this;
	}

	/**
	 * @param string $name
	 * @param callable $callback
	 * @return $this
	 */
	public function addItemCallback($name, callable $callback)
	{
		$this->itemCallbacks[$name] = $callback;

		return $this;
	}

	/**
	 * @return object
	 */
	public function convert()
	{
		$entity = $this->getEntity();
		$metadata = $this->getMetadata();
		foreach ($metadata->getAssociationNames() as $field) {
			$fieldMetadata = $metadata->getAssociationMapping($field);
			if (array_key_exists($field, $this->data) && $this->isAllowedField($field)) {
				$value = $this->data[$field];
				if ($fieldMetadata['type'] == ClassMetadata::MANY_TO_MANY || $fieldMetadata['type'] == ClassMetadata::ONE_TO_MANY) {
					$value = new ArrayCollection($this->data[$field]);
				}
				if (array_key_exists($field, $this->itemCallbacks)) {
					$value = $this->itemCallbacks[$field]($value);
				}
				$metadata->setFieldValue($entity, $field, $value);
			} else {
				if ($fieldMetadata['type'] == ClassMetadata::MANY_TO_MANY || $fieldMetadata['type'] == ClassMetadata::ONE_TO_MANY) {
					$metadata->setFieldValue($entity, $field, new ArrayCollection());
				}
			}
		}
		foreach ($metadata->getFieldNames() as $field) {
			if (array_key_exists($field, $this->data) && $this->isAllowedField($field)) {
				if (array_key_exists($field, $this->itemCallbacks)) {
					$value = $this->itemCallbacks[$field]($value);
				}
				$metadata->setFieldValue($entity, $field, $this->data[$field]);
			}
		}

		return $entity;
	}

	/**
	 * @return ClassMetadata
	 */
	private function getMetadata()
	{
		if (!$this->metadata instanceof ClassMetadata) {
			$this->metadata = $this->entityManager->getClassMetadata($this->class);
		}

		return $this->metadata;
	}

	/**
	 * @return object
	 */
	private function getEntity()
	{
		if ($this->entity === null) {
			$metadata = $this->getMetadata();
			$this->entity = $metadata->newInstance();
		}

		return $this->entity;
	}

	/**
	 * @param string $field
	 * @return bool
	 */
	private function isAllowedField($field)
	{
		if ($this->whiteList !== null && !in_array($field, $this->whiteList)) {
			return false;
		}
		if ($this->ignoreList !== null && in_array($field, $this->ignoreList)) {
			return false;
		}

		return true;
	}
}
