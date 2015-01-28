<?php
/**
 * This file is part of the doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests;

use Tester\TestCase;

/**
 * Class ORMTestCase
 *
 * @package KappaTests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ORMTestCase extends TestCase
{
	/** @var \Kdyby\Doctrine\EntityManager */
	protected $em;

	protected function setUp()
	{
		$container = getContainer();
		$this->em = $container->getByType('Kdyby\Doctrine\EntityManager');
	}
}
