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

use Doctrine\Common\Collections\ArrayCollection;
use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
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
		$class = new TestClass();
		$data = [
			'one' => 'one',
			'two' => 'two',
			'categories' => $class,
			'pub_categories' => $class,
			'items' => $class,
			'pub_items' => $class,
		];
		$entityReflection = new EntityReflection($this->em, $class);
		$entityReflection->invoke('one', $data['one'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('two', $data['two'], EntityReflection::SET_TYPE);
		$entityReflection->invoke('categories', $data['categories'], EntityReflection::ADD_TYPE);
		$entityReflection->invoke('pub_categories', $data['pub_categories'], EntityReflection::ADD_TYPE);
		$entityReflection->invoke('items', $data['items'], EntityReflection::ADD_TYPE);
		$entityReflection->invoke('pub_items', $data['pub_items'], EntityReflection::ADD_TYPE);
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
		$entityReflection = new EntityReflection($this->em, $class);
		Assert::same('one', $entityReflection->get('one'));
		Assert::same('two', $entityReflection->get('two'));
		Assert::type('Doctrine\Common\Collections\Collection', $entityReflection->get('categories', false));
		Assert::type('Doctrine\Common\Collections\Collection', $entityReflection->get('pub_categories', false));
		Assert::true(is_array($entityReflection->get('items')));
		Assert::true(is_array($entityReflection->get('pub_items', true)));
		Assert::count(1, $entityReflection->get('categories'));
		Assert::count(1, $entityReflection->get('pub_categories'));
		Assert::count(1, $entityReflection->get('items'));
		Assert::count(1, $entityReflection->get('pub_items'));
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
