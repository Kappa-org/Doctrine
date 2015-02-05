<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\Mocks;

use Kappa\Doctrine\Queries\Executable;
use Kdyby\Doctrine\QueryBuilder;

/**
 * Class ExecutableQuery
 *
 * @package KappaTests\Mocks
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ExecutableQuery implements Executable
{
	/**
	 * @param QueryBuilder $queryBuilder
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $queryBuilder)
	{
		$queryBuilder->update('KappaTests\Mocks\FormItemsEntity', 'r')
			->set('r.title', $queryBuilder->expr()->literal('UPDATED'))
			->where('r.id = ?0')
			->orWhere('r.id = ?1')
			->setParameters([1, 2]);

		return $queryBuilder;
	}
}
