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
use Nette\Utils\Callback;

/**
 * Class ArrayToEntityConverter
 *
 * @package Kappa\Doctrine\Converters
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayToEntityConverter extends Object
{
	const GET = 'get';

	const SET = 'set';

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
	private $itemResolvers = [];

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
	 * @param mixed $resolver
	 * @return $this
	 */
	public function addItemResolver($name, $resolver)
	{
		$this->itemResolvers[$name] = $resolver;

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
				if ($fieldMetadata['type'] == ClassMetadata::MANY_TO_MANY || $fieldMetadata['type'] == ClassMetadata::ONE_TO_MANY) {
					$value = $this->getResolvedValue($field, true);
				} else {
					$value = $this->getResolvedValue($field);
				}
				$this->invokeValue($field, $value);
			} else {
				if ($fieldMetadata['type'] == ClassMetadata::MANY_TO_MANY || $fieldMetadata['type'] == ClassMetadata::ONE_TO_MANY) {
					$this->invokeValue($field, new ArrayCollection(), false);
				}
			}
		}
		foreach ($metadata->getFieldNames() as $field) {
			if (array_key_exists($field, $this->data) && $this->isAllowedField($field)) {
				$value = $this->getResolvedValue($field);
				$this->invokeValue($field, $value);
			}
		}

		return $entity;
	}

	/**
	 * @param $item
	 * @param bool $convertCollection
	 * @return mixed
	 */
	private function getResolvedValue($item, $convertCollection = false)
	{
		$value = $this->data[$item];
		if ($convertCollection) {
			$value = new ArrayCollection($value);
		}
		if (array_key_exists($item, $this->itemResolvers)) {
			$resolver = $this->itemResolvers[$item];
			if (is_callable($resolver)) {
				$value = $resolver($value);
			} else {
				$value = $resolver;
			}
		}

		return $value;
	}

	/**
	 * @param $field
	 * @param $value
	 * @param bool $useSetter
	 */
	private function invokeValue($field, $value, $useSetter = true)
	{
		$entity = $this->getEntity();
		$method = $this->getMethodName($field, self::SET);
		if (is_callable([$entity, $method]) && $useSetter) {
			Callback::invokeArgs([$entity, $method], [$value]);
		} else {
			$this->getMetadata()->setFieldValue($entity, $field, $value);
		}
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

	/**
	 * @param string $name
	 * @param string $type
	 * @return string
	 */
	private function getMethodName($name, $type)
	{
		$methodName = $type;
		$methodName .= ucfirst($name);

		return $methodName;
	}
}
