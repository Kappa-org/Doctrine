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

use Kappa\Doctrine\Converters\ArrayToEntityConverter;
use KappaTests\Mocks\UserEntity;
use KappaTests\ORMTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ArrayToEntityConverterTest
 *
 * @package doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayToEntityConverterTest extends ORMTestCase
{
	protected function setUp()
	{
		parent::setUp();
	}

	public function testEntityClassname()
	{
		$data = ['name' => 'Tester'];
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		Assert::type('KappaTests\Mocks\UserEntity', $converter->convert());
	}

	public function testEntityObject()
	{
		$data = ['name' => 'Tester'];
		$user = new UserEntity("Joe");
		$converter = new ArrayToEntityConverter($user, $data, $this->em);
		$entity = $converter->convert();
		Assert::type('KappaTests\Mocks\UserEntity', $entity);
		Assert::same('Tester', $entity->getName());
		Assert::same('Tester', $user->getName());
	}

	public function testDefaultConvert()
	{
		$data = [
			'name' => 'Tester',
			'parent' => new UserEntity("Tester parent"),
			'children' => [new UserEntity("children")]
		];
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		$expectedUser = new UserEntity($data['name'], null, $data['children'][0], $data['parent']);
		Assert::equal($expectedUser, $converter->convert());
	}

	public function testIgnore()
	{
		$data = [
			'name' => 'Tester',
			'parent' => new UserEntity("Tester parent"),
			'children' => [new UserEntity("children")]
		];
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		$converter->setIgnoreList(['children', 'parent']);
		$expectedUser = new UserEntity($data['name']);
		Assert::equal($expectedUser, $converter->convert());
	}

	public function testWhiteList()
	{
		$data = [
			'name' => 'Tester',
			'parent' => new UserEntity("Tester parent"),
			'children' => [new UserEntity("children")]
		];
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		$converter->setWhiteList(['name']);
		$expectedUser = new UserEntity($data['name']);
		Assert::equal($expectedUser, $converter->convert());
	}

	public function testCombineWhiteAndIngnoreLists()
	{
		$data = [
			'name' => 'Tester',
			'parent' => new UserEntity("Tester parent"),
			'children' => [new UserEntity("children")]
		];
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		$converter->setWhiteList(['name', 'parent'])
			->setIgnoreList(['parent']);
		$expectedUser = new UserEntity($data['name']);
		Assert::equal($expectedUser, $converter->convert());
	}

	public function testItemCallback()
	{
		$data = [
			'name' => 'Tester',
			'parent' => 'Joe'
		];
		$parent = new UserEntity("Joe");
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		$converter->addItemCallback('parent', function ($name) use ($parent) {return $parent;});
		Assert::equal(new UserEntity("Tester", null, null, $parent), $converter->convert());
	}

	public function testItemValue()
	{
		$data = [
			'name' => 'Tester',
			'parent' => 'Joe'
		];
		$parent = new UserEntity("Joe");
		$converter = new ArrayToEntityConverter('KappaTests\Mocks\UserEntity', $data, $this->em);
		$converter->addItem('parent', $parent);
		Assert::equal(new UserEntity("Tester", null, null, $parent), $converter->convert());
	}
}

\run(new ArrayToEntityConverterTest());
