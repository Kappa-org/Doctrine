<?php
/**
 * Facade.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 13.2.13
 *
 * @package Kappa\Doctrine
 */

namespace Kappa\Doctrine;

use Kappa\Doctrine\Entity;
use Nette\Object;

class Facade extends Object
{
	/** @var \Doctrine\ORM\EntityRepository */
	protected $repository;

	/** @var \Doctrine\ORM\EntityManager */
	protected $entityManager;

	/** @var string */
	protected $repositoryName;

	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 * @throws InvalidStateException
	 */
	public function __construct(\Doctrine\ORM\EntityManager $entityManager)
	{
		if(!$this->repositoryName || !is_string($this->repositoryName))
			throw new InvalidStateException("Class " . __METHOD__ . "error! Name '$this->repositoryName' is not defined or valid string");
		$this->entityManager = $entityManager;
		$this->repository = $this->entityManager->getRepository($this->repositoryName);
	}

	/**
	 * @param $id
	 * @return object
	 */
	public function getOneById($id)
	{
		return $this->repository->findOneBy(array('id' => $id));
	}

	/**
	 * @return array
	 */
	public function getAll()
	{
		return $this->repository->findAll();
	}

	/**
	 * @param array $get
	 * @return object
	 */
	public function getOneBy(array $get)
	{
		return $this->repository->findOneBy($get);
	}

	/**
	 * @param Entity $entity
	 * @param bool $flush
	 * @return mixed
	 */
	public function save(Entity $entity, $flush = true)
	{
		return $this->repository->save($entity, $flush);
	}

	/**
	 * @param Entity $entity
	 * @param bool $flush
	 * @return mixed
	 */
	public function delete(Entity $entity, $flush = true)
	{
		return $this->repository->delete($entity, $flush);
	}
}
