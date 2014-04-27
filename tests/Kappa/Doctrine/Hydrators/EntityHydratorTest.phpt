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

namespace Kappa\Tests\Doctrine;

use Kappa\Doctrine\Hydrators\EntityHydrator;
use Kappa\Tester\TestCase;
use Kappa\Tests\DoctrineMocks\Entity\ExampleEntity;
use Kappa\Tests\DoctrineMocks\Entity\ExampleEntity2;
use Nette\ArrayHash;
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../DoctrineMocks/Entity/ExampleEntity.php';
require_once __DIR__ . '/../../DoctrineMocks/Entity/ExampleEntity2.php';

/**
 * Class EntityHydratorTest
 * @package Kappa\Tests\Doctrine
 */
class EntityHydratorTest extends TestCase
{
	/** @var \Kappa\Doctrine\Hydrators\EntityHydrator */
	private $entityHydrator;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
		$entityManipulator = $container->getByType('Kappa\Doctrine\Helpers\EntityManipulator');
		$this->entityHydrator = new EntityHydrator($entityManager, $entityManipulator);
	}

	public function testBasicHydrate()
	{
		$entity = new ExampleEntity();
		$array = array(
			'name' => 'Budry',
			'email' => 'budry@gmail.com',
			'entities'=> new ExampleEntity2()
		);
		Assert::null($entity->getName());
		Assert::null($entity->getName());
		$this->entityHydrator->hydrate($entity, $array);
		Assert::same('Budry', $entity->getName());
		Assert::same('budry@gmail.com', $entity->getEmail());
		foreach ($entity->getEntities() as $entity) {
			Assert::type('Kappa\Tests\DoctrineMocks\Entity\ExampleEntity2', $entity);
		}
	}
}

\run(new EntityHydratorTest(getContainer()));