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

use Doctrine\ORM\Tools\SchemaTool;
use Kappa\Doctrine\Routes\RouteParamsResolver;
use KappaTests\Mocks\FormItemsEntity;
use KappaTests\ORMTestCase;
use Kdyby\Doctrine\EntityRepository;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class RouteParamsResolverTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class RouteParamsResolverTest extends ORMTestCase
{
	/** @var EntityRepository */
	private $repository;

	/** @var RouteParamsResolver */
	private $routeParamsResolver;

	protected function setUp()
	{
		parent::setUp();
		$entity1 = new FormItemsEntity("entity1 title", "entity1 name");
		$classes = [
			$this->em->getClassMetadata('KappaTests\Mocks\FormItemsEntity'),
		];
		$schemaTool = new SchemaTool($this->em);
		$schemaTool->dropSchema($classes);
		$schemaTool->createSchema($classes);
		$this->em->persist($entity1);
		$this->em->flush();
		$this->repository = $this->em->getRepository('KappaTests\Mocks\FormItemsEntity');
		$this->routeParamsResolver = new RouteParamsResolver($this->repository);
	}

	public function testFilterIn()
	{
		$entity = $this->routeParamsResolver->filterIn(1);
		Assert::type('KappaTests\Mocks\FormItemsEntity', $entity);
		Assert::same('entity1 title', $entity->getTitle());
		Assert::null($this->routeParamsResolver->filterIn(100));
	}

	public function testFilterOut()
	{
		$entity = $this->repository->find(1);
		Assert::same(1, $this->routeParamsResolver->filterOut($entity));
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new RouteParamsResolverTest());
