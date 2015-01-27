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

namespace KappaTests\Doctrine;

use Kappa\Doctrine\Forms\FormItemsCreator;
use Kappa\Doctrine\Reflections\EntityReflectionFactory;
use KappaTests\Entities\FormItemsEntity;
use Kdyby;
use Kdyby\Doctrine\QueryObject;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class FormItemsCreatorTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class FormItemsCreatorTest extends TestCase
{
	/** @var FormItemsCreator */
	private $formItemCreator;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->formItemCreator = new FormItemsCreator($entityManager, new EntityReflectionFactory($entityManager), [
			'identifierColumn' => 'id',
			'valueColumn' => 'name'
		]);
	}

	public function testStringEntity()
	{
		$data = $this->formItemCreator->create(FormItemsEntity::getClassName(), new GetAll());
		Assert::count(1, $data);
		Assert::true(array_key_exists(1, $data));
		Assert::same('John_name', $data[1]);
	}

	public function testDefault()
	{
		$data = $this->formItemCreator->create(new FormItemsEntity(), new GetAll());
		Assert::count(1, $data);
		Assert::true(array_key_exists(1, $data));
		Assert::same('John_name', $data[1]);
	}

	public function testColumnNames()
	{
		$data = $this->formItemCreator->create(new FormItemsEntity(), new GetAll(), 'title', 'name');
		Assert::count(1, $data);
		Assert::true(array_key_exists('John_name', $data));
		Assert::same('John_title', $data['John_name']);
	}
}

/**
 * Class GetAll
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetAll extends QueryObject
{
	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		return $repository->createQueryBuilder('r')
			->select('r');
	}
}

\run(new FormItemsCreatorTest(getContainer()));
