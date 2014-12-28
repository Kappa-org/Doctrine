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

use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class DoctrineHelpersExtensionTest
 *
 * @package Kappa\DoctrineHelpers\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DoctrineHelpersExtensionTest extends TestCase
{
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function testReflection()
	{
		$type = 'Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory';
		Assert::type($type, $this->container->getByType($type));
	}

	public function testHydrators()
	{
		$types = [
			'array' => 'Kappa\DoctrineHelpers\Hydrators\ArrayHydrator',
			'entity' => 'Kappa\DoctrineHelpers\Hydrators\EntityHydrator',
		];
		Assert::type($types['array'], $this->container->getByType($types['array']));
		Assert::type($types['entity'], $this->container->getByType($types['entity']));
	}

	public function testForms()
	{
		$type = 'Kappa\DoctrineHelpers\Forms\FormItemsCreator';
		Assert::type($type, $this->container->getByType($type));
	}
}

\run(new DoctrineHelpersExtensionTest(getContainer()));
