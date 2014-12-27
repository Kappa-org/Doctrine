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

use KappaTests\Entities\GlobalEntity;
use Kappa\DoctrineHelpers\Reflections\EntityReflection;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class EntityManipulatorTest
 *
 * @package Kappa\DoctrineHelpers\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityManipulatorTest extends TestCase
{
	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	public function __construct(Container $container)
	{
		$this->em = $container->getByType('Kdyby\Doctrine\EntityManager');
	}

	public function testInvoke()
	{
		$entity = new GlobalEntity();
		$data = [
			'column' => 'column',
			'pub_column' => 'pub_column',
			'column_s' => 'column_s',
			'column_ies'=> 'column_ies',
			'toOne' => $entity,
			'pub_toOne' => $entity,
			'toOne_s' => $entity,
			'toOne_ies' => $entity,
			'toMany_s' => $entity,
			'toMany_ies' => $entity,
			'pub_toMany_s' => $entity,
		];
		$entityReflection = new EntityReflection($this->em, $entity);
		$entityReflection->invoke('column', $data['column'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('pub_column', $data['pub_column'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('column_s', $data['column_s'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('column_ies', $data['column_ies'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('toOne', $data['toOne'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('pub_toOne', $data['pub_toOne'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('toOne_s', $data['toOne_s'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('toOne_ies', $data['toOne_ies'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('toMany_s', $data['toMany_s'], EntityReflection::ADD_TYPE);
		$entityReflection->invoke('pub_toMany_s', $data['pub_toMany_s'], EntityReflection::ADD_TYPE);
		$entityReflection->invoke('toMany_ies', $data['toMany_ies'], EntityReflection::ADD_TYPE);
		Assert::same($data['column'], $entity->getColumn());
		Assert::same($data['pub_column'], $entity->pub_column);
		Assert::same($data['column_s'], $entity->getColumn_s());
		Assert::same($data['column_ies'], $entity->getColumn_ies());
		Assert::same($data['toOne'], $entity->getToOne());
		Assert::same($data['pub_toOne'], $entity->pub_toOne);
		Assert::same($data['toOne_s'], $entity->getToOne_s());
		Assert::same($data['toOne_ies'], $entity->getToOne_s());
		Assert::type('Doctrine\Common\Collections\Collection', $entity->getToMany_s());
		Assert::type('Doctrine\Common\Collections\Collection', $entity->pub_toMany_s);
		Assert::type('Doctrine\Common\Collections\Collection', $entity->getToMany_ies());
	}

	public function testGet()
	{
		$entity = new GlobalEntity();
		$entity->setColumn("column");
		$entity->pub_column = "pub_column";
		$entity->setColumn_s("column_s");
		$entity->setColumn_ies("column_ies");
		$entity->setToOne($entity);
		$entity->pub_toOne = $entity;
		$entity->setToOne_s($entity);
		$entity->setToOne_ies($entity);
		$entity->addToMany_($entity);
		$entity->pub_toMany_s->add($entity);
		$entity->addToMany_y($entity);
		$entityReflection = new EntityReflection($this->em, $entity);
		Assert::same('column', $entityReflection->get('column'));
		Assert::same('pub_column', $entityReflection->get('pub_column'));
		Assert::same('column_s', $entityReflection->get('column_s'));
		Assert::same('column_ies', $entityReflection->get('column_ies'));
		Assert::type('KappaTests\Entities\GlobalEntity', $entityReflection->get('toOne'));
		Assert::type('KappaTests\Entities\GlobalEntity', $entityReflection->get('toOne_s'));
		Assert::type('KappaTests\Entities\GlobalEntity', $entityReflection->get('toOne_ies'));
		Assert::type('KappaTests\Entities\GlobalEntity', $entityReflection->get('pub_toOne'));
		Assert::true(is_array($entityReflection->get('toMany_s')));
		Assert::true(is_array($entityReflection->get('toMany_ies')));
		Assert::true(is_array($entityReflection->get('pub_toMany_s')));
		Assert::type('Doctrine\Common\Collections\Collection', $entityReflection->get('toMany_s', false));
		Assert::type('Doctrine\Common\Collections\Collection', $entityReflection->get('toMany_ies', false));
		Assert::type('Doctrine\Common\Collections\Collection', $entityReflection->get('pub_toMany_s', false));
	}

	public function testSetterType()
	{
		$entity = new GlobalEntity();
		$entityReflection = new EntityReflection($this->em, $entity);
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('toOne'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('toOne_s'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('toOne_ies'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('pub_toOne'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('column'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('pub_column'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('column_s'));
		Assert::same(EntityReflection::SET_TYPE, $entityReflection->getSetterType('column_ies'));
		Assert::same(EntityReflection::ADD_TYPE, $entityReflection->getSetterType('toMany_s'));
		Assert::same(EntityReflection::ADD_TYPE, $entityReflection->getSetterType('pub_toMany_s'));
		Assert::same(EntityReflection::ADD_TYPE, $entityReflection->getSetterType('toMany_ies'));
	}

	public function testGetProperties()
	{
		$entity = new GlobalEntity();
		$entityReflection = new EntityReflection($this->em, $entity);
		Assert::count(12, $entityReflection->getProperties());
	}
}

\run(new EntityManipulatorTest(getContainer()));
