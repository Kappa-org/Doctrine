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

use Doctrine\ORM\EntityManager;
use Nette\Object;

/**
 * Class EntityReflectionFactory
 *
 * @package Kappa\DoctrineHelpers\Reflections
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityReflectionFactory extends Object
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
	 * @param object $entity
	 * @return EntityReflection
	 */
	public function create($entity)
	{
		return new EntityReflection($this->entityManager, $entity);
	}
}
