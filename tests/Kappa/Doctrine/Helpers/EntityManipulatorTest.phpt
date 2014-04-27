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

use Kappa\Doctrine\Helpers\EntityManipulator;
use Kappa\Tester\TestCase;
use Kappa\Tests\DoctrineMocks\Entity\ExampleEntity;
use Kappa\Tests\DoctrineMocks\Entity\ExampleEntity2;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../DoctrineMocks/Entity/ExampleEntity.php';
require_once __DIR__ . '/../../DoctrineMocks/Entity/ExampleEntity2.php';

/**
 * Class EntityManipulator
 * @package Kappa\Doctrine\Tests
 */
class EntityManipulatorTest extends TestCase
{
	/** @var \Kappa\Doctrine\Helpers\EntityManipulator */
	private $entityManipulator;

	protected function setUp()
	{
		$this->entityManipulator = new EntityManipulator();
	}

	public function testGetValue()
	{
		$entity = new ExampleEntity();
		Assert::null($entity->getName());
		Assert::null($entity->getEmail());
		Assert::null($this->entityManipulator->getValue($entity, 'name'));
		Assert::null($this->entityManipulator->getValue($entity, 'email'));
		$entity->setName('Budry');
		Assert::same('Budry', $this->entityManipulator->getValue($entity, 'name'));

		$self = $this;
		Assert::throws(function() use ($self, $entity) {
			$self->entityManipulator->getValue($entity, 'non-exist');
		}, 'Kappa\Doctrine\ReflectionException');
	}

	public function testSetValue()
	{
		$entity = new ExampleEntity();
		Assert::null($entity->getName());
		Assert::type('Kappa\Tests\DoctrineMocks\Entity\ExampleEntity', $this->entityManipulator->setValue($entity, 'name', 'Budry'));
		Assert::same('Budry', $entity->getName());

		$self = $this;
		Assert::throws(function() use ($self, $entity) {
			$self->entityManipulator->setValue($entity, 'non-exist', 'value');
		}, 'Kappa\Doctrine\ReflectionException');
	}

	public function testAddValue()
	{
		$this->entityManipulator = new EntityManipulator();
		$entity = new ExampleEntity();
		Assert::same(0, count($entity->getEntities()));
		$this->entityManipulator->addValue($entity, 'entities', new ExampleEntity2());
		Assert::same(1, count($entity->getEntities()));
	}
}

\run(new EntityManipulatorTest());