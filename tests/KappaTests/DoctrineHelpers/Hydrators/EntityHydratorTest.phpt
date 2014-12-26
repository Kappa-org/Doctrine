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
use Kappa\DoctrineHelpers\Entities\RelationsEntity;
use Kappa\DoctrineHelpers\Helpers\EntityManipulator;
use Kappa\DoctrineHelpers\Hydrators\EntityHydrator;
use KappaTests\Entities\StaticEntity;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class EntityHydratorTest
 *
 * @package KappaTests\DoctrineHelpers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class EntityHydratorTest extends TestCase
{
	/** @var EntityHydrator */
	private $entityHydrator;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
		$this->entityHydrator = new EntityHydrator($entityManager, new EntityManipulator());
	}

	public function testStaticData()
	{
		$entity = new StaticEntity();
		$data = [
			'string' => "dasasd",
			"public" => 45,
			"int" => 46
		];
		$this->entityHydrator->hydrate($entity, $data);
		Assert::same($data['string'], $entity->getString());
		Assert::same($data['int'], $entity->getInt());
		Assert::same($data['public'], $entity->public);
	}

	public function testRelationsData()
	{
		$entity = new RelationsEntity();
		$data = [
			'oto' => $entity,
			'mto' => $entity,
			'otms' => $entity,
			'mtmies' => $entity,
			'public_mto' => $entity,
		];
		$this->entityHydrator->hydrate($entity, $data);
		Assert::same($entity, $entity->getOto());
		Assert::same($entity, $entity->getMto());
		Assert::same($entity, $entity->public_mto);
		Assert::count(1, $entity->getOtms());
		Assert::count(1, $entity->getMtmies());
	}
}

\run(new EntityHydratorTest(getContainer()));
