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

use Kappa\DoctrineHelpers\Converters\EntityArrayConverter;
use Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory;
use KappaTests\Entities\GlobalEntity;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class EntityArrayConverter_EntityToArrayTest
 *
 * @package Kappa\DoctrineHelpers\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityArrayConverter_EntityToArrayTest extends TestCase
{
	/** @var EntityArrayConverter */
	private $entityArrayConverter;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$em = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->entityArrayConverter = new EntityArrayConverter(new EntityReflectionFactory($em));
	}

	public function testBasicHydrate()
	{
		$entity = new GlobalEntity();
		$entity->addToMany_($entity);
		$entity->addToMany_y($entity);
		$entity->pub_column = 'pub_column';
		$data = $this->entityArrayConverter->entityToArray($entity);
		Assert::same('pub_column', $data['pub_column']);
		Assert::true(is_array($data['toMany_ies']));
		Assert::true(is_array($data['toMany_s']));
		Assert::throws(function() {
			$this->entityArrayConverter->entityToArray('KappaTests\Entities\GlobalEntity');
		}, 'Kappa\DoctrineHelpers\InvalidArgumentException');
	}

	public function testCollectionHydrate()
	{
		$entity = new GlobalEntity();
		$entity->addToMany_($entity);
		$entity->addToMany_y($entity);
		$data = $this->entityArrayConverter->entityToArray($entity, [], false);
		Assert::type('Doctrine\Common\Collections\Collection', $data['toMany_ies']);
		Assert::type('Doctrine\Common\Collections\Collection', $data['toMany_s']);
	}

	public function testIgnore()
	{
		$entity = new GlobalEntity();
		$entity->setColumn('column');
		$data = $this->entityArrayConverter->entityToArray($entity, ['column']);
		Assert::false(array_key_exists('column', $data));
	}

	public function testTransformEntity()
	{
		$entity = new GlobalEntity();
		$entity->setColumn('column');
		$entity->settoOne($entity);
		$data = $this->entityArrayConverter->entityToArray($entity, [], false, ['toOne' => 'column']);
		Assert::same('column', $data['toOne']);
	}
}

\run(new EntityArrayConverter_EntityToArrayTest(getContainer()));
