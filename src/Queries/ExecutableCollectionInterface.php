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

/**
 * Interface ExecutableCollection
 *
 * @package Kappa\Doctrine\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
interface ExecutableCollectionInterface
{
	/**
	 * @return array
	 */
	public function getQueries();
}
