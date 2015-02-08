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

use Nette\Object;

/**
 * Class ExecutableCollection
 *
 * @package Kappa\Doctrine\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ExecutableCollection extends Object implements ExecutableCollectionInterface
{
	/** @var array */
	private $queries;

	/**
	 * @param array $queries
	 */
	public function __construct(array $queries = [])
	{
		$this->queries = $queries;
	}

	/**
	 * @param Executable $query
	 * @return $this
	 */
	public function addQuery(Executable $query)
	{
		$this->queries[] = $query;

		return $this;
	}

	/**
	 * @param string|object $queryName
	 * @return $this
	 */
	public function removeQuery($queryName)
	{
		if (is_object($queryName)) {
			$queryName = get_class($queryName);
		}

		foreach ($this->getQueries() as $key => $query) {
			if ($query instanceof $queryName) {
				unset($this->queries[$key]);
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getQueries()
	{
		return $this->queries;
	}
}
