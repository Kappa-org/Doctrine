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

namespace Kappa\DoctrineHelpers\Tests;

use Kappa\DoctrineHelpers\Converters\EntityArrayConverter;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use KappaTests\Entities\GlobalEntity;
use KappaTests\Entities\RelationIdEntity;
use MyProject\Proxies\__CG__\stdClass;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class EntityArrayConverter_ArrayToEntityTest
 *
 * @package Kappa\DoctrineHelpers\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityArrayConverter_ArrayToEntityTest extends TestCase
{
	/** @var EntityArrayConverter */
	private $entityArrayConverter;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->entityArrayConverter = new EntityArrayConverter(new EntityReflectionFactory($entityManager));
	}

	public function testBasicHydrate()
	{
		$entity = new GlobalEntity();
		$data = [
			'non-column' => 'data',
			'column' => 'column',
			'pub_column' => 'pub_column',
			'toMany_ies' => $entity
		];
		$entity = $this->entityArrayConverter->arrayToEntity($entity, $data);
		Assert::same('column', $entity->getColumn());
		Assert::same('pub_column', $entity->pub_column);
		Assert::count(1, $entity->getToMany_ies());
	}

	public function testBasicHydrateFromNamespace()
	{
		$entity = new GlobalEntity();
		$data = [
			'non-column' => 'data',
			'column' => 'column',
			'pub_column' => 'pub_column',
			'toMany_ies' => $entity
		];
		$entity = $this->entityArrayConverter->arrayToEntity('KappaTests\Entities\GlobalEntity', $data);
		Assert::same('column', $entity->getColumn());
		Assert::same('pub_column', $entity->pub_column);
		Assert::count(1, $entity->getToMany_ies());
	}

	public function testBasicHydrateWithoutReturn()
	{
		$entity = new GlobalEntity();
		$data = [
			'non-column' => 'data',
			'column' => 'column',
			'pub_column' => 'pub_column',
			'toMany_ies' => $entity
		];
		$this->entityArrayConverter->arrayToEntity($entity, $data);
		Assert::same('column', $entity->getColumn());
		Assert::same('pub_column', $entity->pub_column);
		Assert::count(1, $entity->getToMany_ies());
	}

	public function testIgnore()
	{
		$entity = new GlobalEntity();
		$entity->setColumn('test');
		$data = [
			'column' => 'data'
		];
		$entity = $this->entityArrayConverter->arrayToEntity($entity, $data, ['column']);
		Assert::same('test', $entity->getColumn());
	}

	public function testAddIntoCollections()
	{
		$entity = new GlobalEntity();
		$entity->addToMany_($entity);
		$data = [
			'toMany_s' => $entity
		];
		Assert::count(1, $entity->getToMany_s());
		$entity = $this->entityArrayConverter->arrayToEntity($entity, $data);
		Assert::count(2, $entity->getToMany_s());
	}

	public function testHydrateRelationById()
	{
		$entity = new RelationIdEntity();
		$data = [
			'parent' => 1
		];
		$this->entityArrayConverter->arrayToEntity($entity, $data);
		$entity = Assert::type('KappaTests\Entities\RelationIdEntity', $entity->getParent());
	}
}

\run(new EntityArrayConverter_ArrayToEntityTest(getContainer()));
