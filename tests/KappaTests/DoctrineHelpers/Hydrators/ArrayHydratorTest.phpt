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

use Kappa\DoctrineHelpers\Entities\RelationsEntity;
use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
use Kappa\DoctrineHelpers\Hydrators\ArrayHydrator;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use KappaTests\Entities\GlobalEntity;
use KappaTests\Entities\StaticEntity;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ArrayHydratorTest
 *
 * @package KappaTests\DoctrineHelpers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ArrayHydratorTest extends TestCase
{
	/** @var ArrayHydrator */
	private $arrayHydrator;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$em = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->arrayHydrator = new ArrayHydrator(new EntityReflectionFactory($em));
	}

	public function testBasicHydrate()
	{
		$entity = new GlobalEntity();
		$entity->addToMany_($entity);
		$entity->addToMany_y($entity);
		$entity->pub_column = 'pub_column';
		$data = [];
		$this->arrayHydrator->hydrate($data, $entity);
		Assert::same('pub_column', $data['pub_column']);
		Assert::true(is_array($data['toMany_ies']));
		Assert::true(is_array($data['toMany_s']));
	}

	public function testCollectionHydrate()
	{
		$entity = new GlobalEntity();
		$entity->addToMany_($entity);
		$entity->addToMany_y($entity);
		$data = [];
		$this->arrayHydrator->hydrate($data, $entity, [], false);
		Assert::type('Doctrine\Common\Collections\Collection', $data['toMany_ies']);
		Assert::type('Doctrine\Common\Collections\Collection', $data['toMany_s']);
	}

	public function testIgnore()
	{
		$entity = new GlobalEntity();
		$entity->setColumn('column');
		$data = [
			'column' => 'data'
		];
		$this->arrayHydrator->hydrate($data, $entity, ['column']);
		Assert::same('data', $data['column']);
	}

	public function testDefault()
	{
		$entity = new GlobalEntity();
		$entity->setColumn('column');
		$data = [
			'no' => 'data'
		];
		$this->arrayHydrator->hydrate($data, $entity);
		Assert::same('column', $data['column']);
		Assert::same('data', $data['no']);
	}

	public function testTransformEntity()
	{
		$entity = new GlobalEntity();
		$entity->setColumn('column');
		$entity->settoOne($entity);
		$data = [];
		$this->arrayHydrator->hydrate($data, $entity, [], false, ['toOne' => 'column']);
		Assert::same('column', $data['toOne']);
	}
}

\run(new ArrayHydratorTest(getContainer()));
