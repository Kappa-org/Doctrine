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

use Kdyby\Doctrine\EntityManager;

/**
 * Class RouteParamsResolverFactory
 *
 * @package Kappa\Doctrine\Routes
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class RouteParamsResolverFactory
{
	/** @var EntityManager */
	private $entityManager;

	/**
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @param string|object $entity
	 * @return RouteParamsResolver
	 */
	public function create($entity)
	{
		if (is_object($entity)) {
			$entity = get_class($entity);
		}

		return new RouteParamsResolver($this->entityManager->getRepository($entity));
	}
}
