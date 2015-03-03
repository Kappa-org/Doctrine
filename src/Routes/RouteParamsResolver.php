<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Routes;

use Kappa\Doctrine\InvalidArgumentException;
use Kdyby\Doctrine\EntityRepository;

/**
 * Class RouteParamsResolver
 *
 * @package Kappa\Doctrine\Routes
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class RouteParamsResolver
{
	/** @var EntityRepository */
	private $repository;

	/**
	 * @param EntityRepository $repository
	 */
	public function __construct(EntityRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @param int $id
	 * @return null|object
	 */
	public function filterIn($id)
	{
		return $this->repository->find($id);
	}

	/**
	 * @param object $entity
	 * @return int
	 */
	public function filterOut($entity)
	{
		if (!is_object($entity)) {
			throw new InvalidArgumentException(__METHOD__ . ": Entity must be object");
		}

		return $entity->getId();
	}
}
