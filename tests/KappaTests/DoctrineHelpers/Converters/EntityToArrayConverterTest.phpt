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

namespace KappaTests\Doctrine\Converters;

use Doctrine\Common\Collections\ArrayCollection;
use Kappa\Doctrine\Converters\EntityToArrayConverter;
use KappaTests\Entities\UserEntity;
use KappaTests\ORMTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class EntityToArrayConverter
 *
 * @package doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityToArrayConverterTest extends ORMTestCase
{
	protected function setUp()
	{
		parent::setUp();
	}

	public function testDefaultConvert()
	{
		$user = $this->buildEntity();
		$converter = new EntityToArrayConverter($user, $this->em);
		Assert::equal([
			'id' => $user->getId(),
			'name' => $user->getName(),
			'info' => $user->getInfo(),
			'parent' => $user->getParent(),
			'children' => new ArrayCollection($user->getChildren()),
			'users' => new ArrayCollection($user->getUsers())
		], $converter->convert());
	}

	public function testIgnoreList()
	{
		$user = $this->buildEntity();
		$converter = new EntityToArrayConverter($user, $this->em);
		$converter->setIgnoreList(['name', 'parent', 'children']);
		Assert::equal([
			'id' => $user->getId(),
			'info' => $user->getInfo(),
			'users' => new ArrayCollection($user->getUsers())
		], $converter->convert());
	}

	public function testWhiteList()
	{
		$user = $this->buildEntity();
		$converter = new EntityToArrayConverter($user, $this->em);
		$converter->setWhiteList(['name', 'parent', 'children']);
		Assert::equal([
			'name' => 'Tester',
			'parent' => $user->getParent(),
			'children' => new ArrayCollection($user->getChildren())
		], $converter->convert());
	}

	public function testWhiteListAndIgnoreList()
	{
		$user = $this->buildEntity();
		$converter = new EntityToArrayConverter($user, $this->em);
		$converter->setWhiteList(['name', 'parent', 'children'])
			->setIgnoreList(['name', 'parent']);
		Assert::equal([
			'children' => new ArrayCollection($user->getChildren())
		], $converter->convert());
	}

	public function testColumnCallback()
	{
		$user = $this->buildEntity();
		$converter = new EntityToArrayConverter($user, $this->em);
		$converter->addColumnCallback('name', function ($name) {return strtoupper($name);});
		Assert::same(strtoupper($user->getName()), $converter->convert()['name']);
	}

	public function testRelationCallback()
	{
		$user = $this->buildEntity();
		$converter = new EntityToArrayConverter($user, $this->em);
		$converter->addRelationCallback('parent', function (UserEntity $user) {return $user->getName();});
		Assert::same($user->getParent()->getName(), $converter->convert()['parent']);
	}

	/**
	 * @return UserEntity
	 */
	private function buildEntity()
	{
		$info = new UserEntity("Tester info");
		$parent = new UserEntity("Tester parent");
		$children = new UserEntity("Tester children");
		$users = new UserEntity("Tester users");
		$user = new UserEntity("Tester");
		$user->setInfo($info)
			->setParent($parent)
			->addChildren($children)
			->addUser($users);

		return $user;
	}
}

\run(new EntityToArrayConverterTest());
