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

use Kappa\Tester\TestCase;
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class DoctrineExtensionTest
 * @package Kappa\Doctrine\Tests
 */
class DoctrineExtensionTest extends TestCase
{
	/** @var \Nette\DI\Container */
	private $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function testEntityManipulator()
	{
		Assert::type('Kappa\Doctrine\Helpers\EntityManipulator', $this->container->getService('doctrine.entityManipulator'));
	}
}

\run(new DoctrineExtensionTest(getContainer()));