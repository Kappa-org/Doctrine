<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace Kappa\Doctrine\Tests;

use Kappa\Doctrine\Queries\ExecutableCollection;
use KappaTests\Mocks\ExecutableQuery;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class QueryCollectionTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class QueryCollectionTest extends TestCase
{
	/** @var ExecutableCollection */
	private $executableCollection;

	protected function setUp()
	{
		$this->executableCollection = new ExecutableCollection();
	}

	public function testInstance()
	{
		$expected = [new ExecutableQuery()];
		$executableCollectionWithData = new ExecutableCollection($expected);
		Assert::equal($expected, $executableCollectionWithData->getQueries());
		Assert::equal([], $this->executableCollection->getQueries());
	}

	public function testAdd()
	{
		$query = new ExecutableQuery();
		Assert::type(get_class($this->executableCollection), $this->executableCollection->addQuery($query));
		Assert::equal([$query], $this->executableCollection->getQueries());
	}

	public function testRemoveObject()
	{
		$query = new ExecutableQuery();
		$this->executableCollection->addQuery($query)
			->addQuery($query);
		$this->executableCollection->removeQuery($query);
		Assert::equal([], $this->executableCollection->getQueries());
	}

	public function testRemoveString()
	{
		$query = new ExecutableQuery();
		$this->executableCollection->addQuery($query)
			->addQuery($query);
		$this->executableCollection->removeQuery(get_class($query));
		Assert::equal([], $this->executableCollection->getQueries());
	}

	public function testRemoveWithoutRemove()
	{
		$query = new ExecutableQuery();
		$this->executableCollection->addQuery($query)
			->addQuery($query);
		$this->executableCollection->removeQuery("stdClass");
		Assert::equal([$query, $query], $this->executableCollection->getQueries());
	}

	public function testGetQueries()
	{
		$query = new ExecutableQuery();
		$this->executableCollection->addQuery($query)
			->addQuery($query);
		Assert::equal([$query, $query], $this->executableCollection->getQueries());
	}
}

\run(new QueryCollectionTest());
