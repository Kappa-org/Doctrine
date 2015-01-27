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

namespace KappaTests\Doctrine;

use Kappa\Doctrine\Converters\Converter;
use KappaTests\ORMTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ConverterTest
 *
 * @package doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ConverterTest extends ORMTestCase
{
	/** @var \Kappa\Doctrine\Converters\Converter */
	private $converter;

	protected function setUp()
	{
		parent::setUp();
		$this->converter = new Converter($this->em);
	}

	public function testEntityToArray()
	{
		Assert::type('Kappa\Doctrine\Converters\EntityToArrayConverter', $this->converter->entityToArray(new \stdClass()));
	}

	public function testArrayToEntity()
	{
		Assert::type('Kappa\Doctrine\Converters\ArrayToEntityConverter', $this->converter->arrayToEntity('stdClass', ['a']));
	}
}

\run(new ConverterTest());
