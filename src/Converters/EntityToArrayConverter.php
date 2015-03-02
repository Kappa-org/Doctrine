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

use Doctrine\ORM\Mapping\ClassMetadata;
use Kappa\Doctrine\InvalidArgumentException;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class EntityToArrayConverter
 *
 * @package Kappa\Doctrine\Converters
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityToArrayConverter extends Object
{
	/** @var EntityManager */
	private $entityManager;

	/** @var object */
	private $entity;

	/** @var \Doctrine\ORM\Mapping\ClassMetadata */
	private $metadata;

	/** @var null|array */
	private $ignoreList = null;

	/** @var null|array */
	private $whiteList = null;

	/** @var array */
	private $filedResolvers = [];

	/**
	 * @param object $entity
	 * @param EntityManager $entityManager
	 */
	public function __construct($entity, EntityManager $entityManager)
	{
		if (!is_object($entity)) {
			throw new InvalidArgumentException(__METHOD__ . ": Argument must ne instance of entity object");
		}
		$this->entity = $entity;
		$this->entityManager = $entityManager;
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
	public function addFieldResolver($name, $resolver)
	{
		$this->filedResolvers[$name] = $resolver;

		return $this;
	}

	/**
	 * @return array
	 */
	public function convert()
	{
		$result = [];
		$metadata = $this->getMetadata();
		$fields = array_merge($metadata->getFieldNames(), $metadata->getAssociationNames());
		foreach($fields as $field) {
			if ($this->isAllowedField($field)) {
				$value = $this->getResolvedValue($field);
				$result[$field] = $value;
			}
		}

		return $result;
	}

	/**
	 * @param string $filed
	 * @return mixed
	 */
	private function getResolvedValue($filed)
	{
		$value = $this->getMetadata()->getFieldValue($this->entity, $filed);
		if (array_key_exists($filed, $this->filedResolvers)) {
			$resolver = $this->filedResolvers[$filed];
			if (is_callable($resolver)) {
				$value = $resolver($value);
			} else {
				$value = $resolver;
			}
		}

		return $value;
	}

	/**
	 * @return ClassMetadata
	 */
	private function getMetadata()
	{
		if (!$this->metadata instanceof ClassMetadata) {
			$this->metadata = $this->entityManager->getClassMetadata(get_class($this->entity));
		}

		return $this->metadata;
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
