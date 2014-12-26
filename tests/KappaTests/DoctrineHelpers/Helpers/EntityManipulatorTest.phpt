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

use Doctrine\Common\Collections\ArrayCollection;
use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
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
	/** @var EntityManipulator */
	private $entityManipulator;

	protected function setUp()
	{
		$this->entityManipulator = new EntityManipulator();
	}

	public function testInvoke()
	{
		$class = new TestClass();
		$data = [
			'one' => 'one',
			'two' => 'two',
			'categories' => $class,
			'pub_categories' => $class,
			'items' => $class,
			'pub_items' => $class,
		];
		$this->entityManipulator->invoke($class, 'one', $data['one'], EntityManipulator::SET_TYPE);
		$this->entityManipulator->invoke($class, 'two', $data['two'], EntityManipulator::SET_TYPE);
		$this->entityManipulator->invoke($class, 'categories', $data['categories'], EntityManipulator::ADD_TYPE);
		$this->entityManipulator->invoke($class, 'pub_categories', $data['pub_categories'], EntityManipulator::ADD_TYPE);
		$this->entityManipulator->invoke($class, 'items', $data['items'], EntityManipulator::ADD_TYPE);
		$this->entityManipulator->invoke($class, 'pub_items', $data['pub_items'], EntityManipulator::ADD_TYPE);
		Assert::same($data['one'], $class->getOne());
		Assert::same($data['two'], $class->two);
		Assert::count(1, $class->getCategories());
		Assert::count(1, $class->getItems());
		Assert::count(1, $class->pub_categories);
		Assert::count(1, $class->pub_items);
	}

	public function testGet()
	{
		$class = new TestClass();
		$class->setOne('one');
		$class->two = 'two';
		$class->addCategory($class);
		$class->addItem($class);
		$class->pub_categories->add($class);
		$class->pub_items->add($class);
		Assert::same('one', $this->entityManipulator->get($class, 'one'));
		Assert::same('two', $this->entityManipulator->get($class, 'two'));
		Assert::count(1, $this->entityManipulator->get($class, 'categories'));
		Assert::count(1, $this->entityManipulator->get($class, 'pub_categories'));
		Assert::count(1, $this->entityManipulator->get($class, 'items'));
		Assert::count(1, $this->entityManipulator->get($class, 'pub_items'));
	}
}

/**
 * Class TestClass
 *
 * @package Kappa\DoctrineHelpers\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TestClass
{
	private $one;

	public $two;

	/** @var ArrayCollection */
	private $categories;

	/** @var ArrayCollection */
	private $items;

	/** @var ArrayCollection  */
	public $pub_categories;

	/** @var ArrayCollection */
	public $pub_items;

	public function __construct()
	{
		$this->items = new ArrayCollection();
		$this->pub_items= new ArrayCollection();
		$this->categories = new ArrayCollection();
		$this->pub_categories = new ArrayCollection();
	}

	public function setOne($one)
	{
		$this->one = $one;
	}

	public function getOne()
	{
		return $this->one;
	}

	public function addCategory($category)
	{
		$this->categories->add($category);
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function addItem($item)
	{
		$this->items->add($item);
	}

	public function getItems()
	{
		return $this->items;
	}
}

\run(new EntityManipulatorTest(getContainer()));
