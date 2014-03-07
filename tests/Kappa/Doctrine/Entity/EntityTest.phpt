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
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../../data/ExampleEntity.php';

class EntityTest extends TestCase
{
	public function testEntityFiller()
	{
		$entity = new ExampleEntity();
		Assert::null($entity->getName());
		Assert::null($entity->getEmail());
		$entity = new ExampleEntity(array('name' => 'Budry'));
		Assert::same('Budry', $entity->getName());
		Assert::null($entity->getEmail());
		Assert::throws(function() {
			new ExampleEntity(array('some' => 'next'));
		}, 'Kappa\Doctrine\InvalidPropertyNameException');
	}
}

\run(new EntityTest());