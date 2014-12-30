<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\Reflections;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Proxy\Proxy;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;
use Nette\Utils\Callback;

/**
 * Class EntityReflection
 *
 * @package Kappa\DoctrineHelpers\Reflections
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityReflection extends Object
{
	const SET_TYPE = "set";

	const ADD_TYPE = "add";

	const GET_TYPE = "get";

	/** @var EntityManager */
	private $entityManager;

	/** @var object */
	private $entity;

	/**
	 * @param EntityManager $entityManager
	 * @param object $entity
	 */
	public function __construct(EntityManager $entityManager, $entity)
	{
		$this->entityManager = $entityManager;
		$this->entity = $entity;
	}

	/**
	 * @return array
	 */
	public function getProperties()
	{
		$mapping = $this->entityManager->getClassMetadata($this->getEntityName());

		return array_merge($mapping->getFieldNames(), $mapping->getAssociationNames());
	}

	/**
	 * @param string $columnName
	 * @return string
	 */
	public function getSetterType($columnName)
	{
		$metadata = $this->entityManager->getClassMetadata($this->getEntityName());
		try {
			$assoc = $metadata->getAssociationMapping($columnName);
			if ($assoc['type'] == ClassMetadata::ONE_TO_ONE || $assoc['type'] == ClassMetadata::MANY_TO_ONE) {
				return self::SET_TYPE;
			} else {
				return self::ADD_TYPE;
			}
		} catch (MappingException $e) {
			return self::SET_TYPE;
		}
	}

	/**
	 * @param string $column
	 * @param mixed $value
	 * @param string $type
	 */
	public function invoke($column, $value, $type)
	{
		$metadata = $this->entityManager->getClassMetadata($this->getEntityName());
		if (in_array($column, $metadata->getAssociationNames()) && is_numeric($value)) {
			$dao = $this->entityManager->getDao($metadata->getAssociationMapping($column)['targetEntity']);
			$targetEntity = $dao->find($value);
			$value = $targetEntity;
		}
		$ref = new \ReflectionProperty($this->getEntityName(), $column);
		if ($ref->isPublic()) {
			if ($ref->getValue($this->entity) instanceof Collection) {
				$ref->getValue($this->entity)->add($value);
			} else {
				$ref->setValue($this->entity, $value);
			}
		} else {
			Callback::invokeArgs([$this->entity, $this->getMethodName($type, $column)], [$value]);
		}
	}

	/**
	 * @param string $column
	 * @param bool $convertCollections
	 * @return mixed
	 */
	public function get($column, $convertCollections = true, array $transformEntity = null)
	{
		$ref = new \ReflectionProperty($this->getEntityName(), $column);
		if ($ref->isPublic()) {
			$retVal =  $ref->getValue($this->entity);
		} else {
			$retVal = Callback::invoke([$this->entity, $this->getMethodName(self::GET_TYPE, $column)]);
		}
		if ($convertCollections && $retVal instanceof Collection) {
			return $retVal->toArray();
		} else {
			if ($transformEntity !== null && array_key_exists($column, $transformEntity) && $this->isAssocMapping($column) && $retVal !== null) {
				$entityReflection = new EntityReflection($this->entityManager, $retVal);

				return $entityReflection->get($transformEntity[$column]);
			}
			return $retVal;
		}
	}

	private function getEntityName()
	{
		return ClassUtils::getRealClass(get_class($this->entity));
	}

	/**
	 * @param string $column
	 * @return bool
	 */
	private function isAssocMapping($column)
	{
		return in_array($column, $this->entityManager->getClassMetadata($this->getEntityName())->getAssociationNames());
	}

	/**
	 * @param string $type
	 * @param string $column
	 * @return string
	 */
	private function getMethodName($type, $column)
	{
		$name = $type;
		if ($type == self::ADD_TYPE) {
			if (substr($column, -3) == 'ies') {
				$column = substr($column, 0, strlen($column) - 3) . 'y';
			} elseif (substr($column, -1) == 's') {
				$column = substr($column, 0, strlen($column) - 1);
			}
		}
		$name .= ucfirst($column);

		return $name;
	}
}
