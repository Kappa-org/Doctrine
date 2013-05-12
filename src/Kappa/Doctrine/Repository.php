<?php
/**
 * Repository.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 23.2.13
 *
 * @package Kappa\Doctrine
 */

namespace Kappa\Doctrine;

use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository
{
	/**
	 * @param Entity $entity
	 * @param bool $flush
	 * @return Repository
	 */
	public function delete(Entity $entity, $flush = true)
	{
		$this->_em->remove($entity);
		if($flush) {
			$this->flush();
		}
		return $this;
	}

	/**
	 * @param Entity $entity
	 * @param bool $flush
	 * @return Repository
	 */
	public function save(Entity $entity, $flush = true)
	{
		$this->_em->persist($entity);
		if($flush) {
			$this->flush();
		}
		return $this;
	}

	/**
	 * @return Repository
	 */
	public function flush()
	{
		$this->_em->flush();
		return $this;
	}
}
