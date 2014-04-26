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
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../DoctrineMocks/Entity/ExampleEntity.php';

/**
 * Class EntityManipulator
 * @package Kappa\Doctrine\Tests
 */
class EntityManipulatorTest extends TestCase
{
	public function testManipulation()
	{
		$entityManipulator = new EntityManipulator();
		$entity = new ExampleEntity();
		Assert::null($entity->getName());
		Assert::null($entity->getEmail());
		Assert::null($entityManipulator->getValue($entity, 'name'));
		Assert::null($entityManipulator->getValue($entity, 'email'));
		Assert::type('Kappa\Tests\DoctrineMocks\Entity\ExampleEntity', $entityManipulator->setValue($entity, 'name', 'Budry'));
		Assert::type('Kappa\Tests\DoctrineMocks\Entity\ExampleEntity', $entityManipulator->setValue($entity, 'email', 'budry@gmail.com'));
		Assert::same('Budry', $entityManipulator->getValue($entity, 'name'));
		Assert::same('budry@gmail.com', $entityManipulator->getValue($entity, 'email'));

		Assert::throws(function() use ($entityManipulator, $entity) {
			$entityManipulator->setValue($entity, 'non-exist', 'value');
		}, 'Kappa\Doctrine\ReflectionException');
		Assert::throws(function() use ($entityManipulator, $entity) {
			$entityManipulator->getValue($entity, 'non-exist');
		}, 'Kappa\Doctrine\ReflectionException');
	}
}

\run(new EntityManipulatorTest());