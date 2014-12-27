<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace KappaTests\DoctrineHelpers;

use Kappa\DoctrineHelpers\Entities\RelationsEntity;
use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
use Kappa\DoctrineHelpers\Hydrators\ArrayHydrator;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use KappaTests\Entities\StaticEntity;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ArrayHydratorTest
 *
 * @package KappaTests\DoctrineHelpers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayHydratorTest extends TestCase
{
	/** @var ArrayHydrator */
	private $arrayHydrator;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$em = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->arrayHydrator = new ArrayHydrator(new EntityReflectionFactory($em));
	}

	public function testStaticData()
	{
		$entity = new StaticEntity();
		$entity->setString('string');
		$entity->setInt(40);
		$entity->public = 'public';
		$array = [];
		Assert::count(0, $array);
		$this->arrayHydrator->hydrate($array, $entity);
		Assert::true(array_key_exists('string', $array));
		Assert::true(array_key_exists('int', $array));
		Assert::true(array_key_exists('public', $array));
		Assert::same('string', $array['string']);
		Assert::same(40, $array['int']);
		Assert::same('public', $array['public']);
	}

	public function testIgnore()
	{
		$entity = new StaticEntity();
		$entity->setString('string');
		$entity->setInt(40);
		$entity->public = 'public';
		$array = [];
		Assert::count(0, $array);
		$this->arrayHydrator->hydrate($array, $entity, ['string']);
		Assert::false(array_key_exists('string', $array));
		Assert::true(array_key_exists('int', $array));
		Assert::true(array_key_exists('public', $array));
	}

	public function testDefault()
	{
		$entity = new StaticEntity();
		$entity->setString('string');
		$entity->setInt(40);
		$entity->public = 'public';
		$array = [
			'test' => 30
		];
		Assert::same(30, $array['test']);
	}

	public function testRelations()
	{
		$entity = new RelationsEntity();
		$entity->setOto($entity);
		$entity->setMto($entity);
		$entity->addOtm($entity);
		$entity->addMtmy($entity);
		$entity->public_mto = $entity;
		$array = [];
		$array_collections = [];
		$this->arrayHydrator->hydrate($array, $entity);
		$this->arrayHydrator->hydrate($array_collections, $entity, [], false);
		Assert::count(6, $array);
		Assert::true(array_key_exists('oto', $array));
		Assert::true(array_key_exists('mto', $array));
		Assert::true(array_key_exists('otms', $array));
		Assert::true(array_key_exists('mtmies', $array));
		Assert::true(array_key_exists('public_mto', $array));
		Assert::true(array_key_exists('oto', $array_collections));
		Assert::true(array_key_exists('mto', $array_collections));
		Assert::true(array_key_exists('otms', $array_collections));
		Assert::true(array_key_exists('mtmies', $array_collections));
		Assert::true(array_key_exists('public_mto', $array_collections));
		Assert::true(is_array($array['mtmies']));
		Assert::true(is_array($array['otms']));
		Assert::true(is_array($array_collections['mtmies']));
		Assert::type('Doctrine\Common\Collections\Collection', $array_collections['otms']);
	}
}

\run(new ArrayHydratorTest(getContainer()));
