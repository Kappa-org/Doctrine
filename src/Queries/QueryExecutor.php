<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Queries;

use Kappa\Doctrine\InvalidArgumentException;
use Kappa\Doctrine\NotQueryBuilderException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\QueryBuilder;
use Nette\Object;

/**
 * Class QueryExecutor
 *
 * @package Kappa\Doctrine\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class QueryExecutor extends Object
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
	 * @param Executable|ExecutableCollectionInterface $query
	 */
	public function execute($query)
	{
		if (!$query instanceof Executable && !$query instanceof ExecutableCollectionInterface) {
			throw new InvalidArgumentException(__METHOD__ . " Query can be only instance of Exectable or ExecutableCollection");
		}
		$builder = $this->entityManager->createQueryBuilder();
		if ($query instanceof Executable) {
			$queries = [$query];
		} else {
			$queries = $query->getQueries();
		}
		foreach ($queries as $query) {
			$qb = $query->build($builder);
			if (!$qb instanceof QueryBuilder) {
				throw new NotQueryBuilderException('Executable object ' . get_class($query) . ' must returns instance of QueryBuilder');
			}
			$qb->getQuery()->execute();
		}
	}
}
