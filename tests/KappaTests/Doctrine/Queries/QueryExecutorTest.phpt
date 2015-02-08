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

namespace KappaTests\Doctrine\Queries;

use Doctrine\ORM\Tools\SchemaTool;
use Kappa\Doctrine\Queries\QueryExecutor;
use KappaTests\Mocks\ExecutableCollection;
use KappaTests\Mocks\ExecutableQuery;
use KappaTests\Mocks\FormItemsEntity;
use KappaTests\Mocks\GetAll;
use KappaTests\ORMTestCase;
use Kdyby\Doctrine\EntityRepository;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class QueryExecutorTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class QueryExecutorTest extends ORMTestCase
{
	/** @var QueryExecutor */
	private $queryExecutor;

	/** @var EntityRepository */
	private $dao;

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
		$this->dao = $this->em->getRepository('KappaTests\Mocks\FormItemsEntity');
		$this->queryExecutor = new QueryExecutor($this->em);
	}

	public function testBuild()
	{
		$entity = $this->dao->find(1);
		Assert::same("entity1 title", $entity->getTitle());
		$this->queryExecutor->execute(new ExecutableQuery());
		$this->em->refresh($entity);
		Assert::same("UPDATED", $entity->getTitle());
	}

	public function testBuildWithCollection()
	{
		$entity = $this->dao->find(1);
		Assert::same("entity1 title", $entity->getTitle());
		$this->queryExecutor->execute(new ExecutableCollection());
		$this->em->refresh($entity);
		Assert::same("UPDATED", $entity->getTitle());
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new QueryExecutorTest());
