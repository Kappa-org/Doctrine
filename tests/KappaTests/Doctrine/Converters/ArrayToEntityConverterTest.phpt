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

namespace KappaTests\Doctrine;

use Kappa\Doctrine\Converters\ArrayToEntityConverter;
use KappaTests\Entities\UserEntity;
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

	public function testDefaultConvert()
	{
		$data = [
			'name' => 'Tester',
			'parent' => new UserEntity("Tester parent"),
			'children' => [new UserEntity("children")]
		];
		$converter = new ArrayToEntityConverter('KappaTests\Entities\UserEntity', $data, $this->em);
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
		$converter = new ArrayToEntityConverter('KappaTests\Entities\UserEntity', $data, $this->em);
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
		$converter = new ArrayToEntityConverter('KappaTests\Entities\UserEntity', $data, $this->em);
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
		$converter = new ArrayToEntityConverter('KappaTests\Entities\UserEntity', $data, $this->em);
		$converter->setWhiteList(['name', 'parent'])
			->setIgnoreList(['parent']);
		$expectedUser = new UserEntity($data['name']);
		Assert::equal($expectedUser, $converter->convert());
	}
}

\run(new ArrayToEntityConverterTest());
