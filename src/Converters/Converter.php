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

use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class Converter
 *
 * @package Kappa\Doctrine\Converters
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class Converter extends Object
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
	 * @return EntityToArrayConverter
	 */
	public function entityToArray($entity)
	{
		return new EntityToArrayConverter($entity, $this->entityManager);
	}

	/**
	 * @param string|object $entity
	 * @param array $data
	 * @return ArrayToEntityConverter
	 */
	public function arrayToEntity($entity, array $data)
	{
		return new ArrayToEntityConverter($entity, $data, $this->entityManager);
	}
}
