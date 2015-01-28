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

use Kappa\Doctrine\Converters\Converter;
use Kappa\Doctrine\Managers\CrudManagerFactory;
use KappaTests\Entities\UserEntity;
use KappaTests\ORMTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class CrudManagerFactory
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class CrudManagerFactoryTest extends ORMTestCase
{
	/** @var \Kappa\Doctrine|Managers\CrudManagerFactory */
	private $crudManagerFactory;

	protected function setUp()
	{
		parent::setUp();
		$this->crudManagerFactory = new CrudManagerFactory($this->em, new Converter($this->em));
	}

	public function testCreateFromString()
	{
		Assert::type('Kappa\Doctrine\Managers\CrudManager', $this->crudManagerFactory->create('KappaTests\Entities\UserEntity'));
	}

	public function testCreateFromObject()
	{
		Assert::type('Kappa\Doctrine\Managers\CrudManager', $this->crudManagerFactory->create(new UserEntity("Tester")));
	}
}

\run(new CrudManagerFactoryTest());
