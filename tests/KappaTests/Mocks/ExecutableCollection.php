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

use Nette\Object;

/**
 * Class QueryCollection
 *
 * @package KappaTests\Mocks
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ExecutableCollection extends Object implements \Kappa\Doctrine\Queries\ExecutableCollection
{
	/**
	 * @return array
	 */
	public function getQueries()
	{
		return [new ExecutableQuery()];
	}
}
