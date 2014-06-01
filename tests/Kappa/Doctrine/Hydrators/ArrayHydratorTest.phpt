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

use Kappa\Doctrine\Hydrators\ArrayHydrator;
use Kappa\Tester\TestCase;
use Kappa\Tests\DoctrineMocks\Entity\ExampleEntity;
use Kappa\Tests\DoctrineMocks\Entity\ExampleEntity2;
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class ArrayHydratorTest extends TestCase
{
	/** @var \Kappa\Doctrine\Hydrators\ArrayHydrator */
	private $arrayHydrator;

	public function __construct(Container $container)
	{
		$entityManager = $container->getByType('Doctrine\ORM\EntityManager');
		$entityManipulator = $container->getByType('Kappa\Doctrine\Helpers\EntityManipulator');
		$this->arrayHydrator = new ArrayHydrator($entityManager, $entityManipulator);
	}

	public function testBasicHydrate()
	{
		$entity = new ExampleEntity();
		$entity->setName('Budry');
		$entity->setEmail('budry@example.com');
		$entity->addEntity(new ExampleEntity2());
		$array = array();
		Assert::same(0, count($array));
		$this->arrayHydrator->hydrate($array, $entity);
		Assert::same(4, count($array));
		Assert::same($entity->getName(), $array['name']);
		Assert::same($entity->getEmail(), $array['email']);
		Assert::equal(array(new ExampleEntity2()), $array['entities']);
	}

	public function testIgnore()
	{
		$entity = new ExampleEntity();
		$entity->setName('Budry');
		$entity->setEmail('budry@example.com');
		$array = array();
		$this->arrayHydrator->hydrate($array, $entity, array('email'));
		Assert::same(3, count($array));
		Assert::same($entity->getName(), $array['name']);
	}
}

\run(new ArrayHydratorTest(getContainer()));