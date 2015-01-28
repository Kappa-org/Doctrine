<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Managers;

use Kappa\Doctrine\Converters\Converter;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Class CrudManagerFactory
 *
 * @package Kappa\Doctrine\Managers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class CrudManagerFactory extends Object
{
	/** @var EntityManager */
	private $entityManager;

	/** @var Converter */
	private $converter;

	/**
	 * @param EntityManager $entityManager
	 * @param Converter $converter
	 */
	public function __construct(EntityManager $entityManager, Converter $converter)
	{
		$this->entityManager = $entityManager;
		$this->converter = $converter;
	}

	/**
	 * @param string|object $entity
	 * @return CrudManager
	 */
	public function create($entity)
	{
		if (is_object($entity)) {
			$entity = get_class($entity);
		}

		return new CrudManager($this->entityManager->getDao($entity), $this->converter);
	}
}
