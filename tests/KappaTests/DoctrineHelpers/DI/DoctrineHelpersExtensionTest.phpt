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

use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class DoctrineExtensionTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DoctrineExtensionTest extends TestCase
{
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function testReflection()
	{
		$type = 'Kappa\Doctrine\Reflections\EntityReflectionFactory';
		Assert::type($type, $this->container->getByType($type));
	}

	public function testConverter()
	{
		$type = 'Kappa\Doctrine\Converters\Converter';
		Assert::type($type, $this->container->getByType($type));
	}

	public function testForms()
	{
		$type = 'Kappa\Doctrine\Forms\FormItemsCreator';
		Assert::type($type, $this->container->getByType($type));
	}
}

\run(new DoctrineExtensionTest(getContainer()));
