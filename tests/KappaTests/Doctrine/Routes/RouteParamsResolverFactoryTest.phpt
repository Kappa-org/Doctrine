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

use Kappa\Doctrine\Routes\RouteParamsResolverFactory;
use KappaTests\Mocks\FormItemsEntity;
use KappaTests\ORMTestCase;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class RouteParamsResolverFactoryTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class RouteParamsResolverFactoryTest extends ORMTestCase
{
	/** @var RouteParamsResolverFactory */
	private $factory;

	protected function setUp()
	{
		parent::setUp();
		$this->factory = new RouteParamsResolverFactory($this->em);
	}

	public function testCreateString()
	{
		Assert::type('Kappa\Doctrine\Routes\RouteParamsResolver', $this->factory->create('KappaTests\Mocks\FormItemsEntity'));
	}

	public function testCreateObject()
	{
		Assert::type('Kappa\Doctrine\Routes\RouteParamsResolver', $this->factory->create(new FormItemsEntity("title", "name")));
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new RouteParamsResolverFactoryTest());
