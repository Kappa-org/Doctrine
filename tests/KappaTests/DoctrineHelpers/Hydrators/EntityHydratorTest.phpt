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

use Kappa\DoctrineHelpers\Hydrators\EntityHydrator;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use KappaTests\Entities\GlobalEntity;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class EntityHydratorTest
 *
 * @package KappaTests\DoctrineHelpers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityHydratorTest extends TestCase
{
	/** @var EntityHydrator */
	private $entityHydrator;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->entityHydrator = new EntityHydrator(new EntityReflectionFactory($entityManager));
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
		$this->entityHydrator->hydrate($entity, $data);
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
		$this->entityHydrator->hydrate($entity, $data, ['column']);
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
		$this->entityHydrator->hydrate($entity, $data);
		Assert::count(2, $entity->getToMany_s());
	}
}

\run(new EntityHydratorTest(getContainer()));
