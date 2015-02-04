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

namespace KappaTests\Doctrine\Managers;

use Doctrine\ORM\Tools\SchemaTool;
use Kappa\Doctrine\Converters\Converter;
use Kappa\Doctrine\Managers\CrudManager;
use KappaTests\Mocks\UserEntity;
use KappaTests\ORMTestCase;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class CrudManagerTest
 *
 * @package Kappa\Doctrine\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class CrudManagerTest extends ORMTestCase
{
	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;

	/** @var \Kappa\Doctrine\Managers\CrudManager */
	private $crudManager;

	protected function setUp()
	{
		parent::setUp();
		Environment::lock("database", dirname(TEMP_DIR));
		$user = new UserEntity('Tester');
		$classes = [
			$this->em->getClassMetadata('KappaTests\Mocks\UserEntity'),
		];
		$schemaTool = new SchemaTool($this->em);
		$schemaTool->dropSchema($classes);
		$schemaTool->createSchema($classes);
		$this->dao = $this->em->getDao('KappaTests\Mocks\UserEntity');
		$this->dao->save($user);
		$this->crudManager = new CrudManager($this->dao, new Converter($this->em));
	}

	public function testCreate()
	{
		Assert::count(1, $this->dao->findAll());
		Assert::same("Tester", $this->dao->find(1)->getName());
		$data = [
			'name' => 'Joe'
		];
		$this->crudManager->create($data);
		Assert::count(2, $this->dao->findAll());
		Assert::same("Joe", $this->dao->find(2)->getName());
	}

	public function testUpdate()
	{
		$original = $this->dao->find(1);
		Assert::same("Tester", $original->getName());
		$this->crudManager->update(1, ['name' => 'Joe']);
		$new = $this->dao->find(1);
		Assert::same("Joe", $new->getName());
		Assert::exception(function () {
			$this->crudManager->update(100, []);
		}, 'Kappa\Doctrine\EntityNotFoundException');
	}

	public function testDelete()
	{
		Assert::count(1, $this->dao->findAll());
		$this->crudManager->delete(1);
		Assert::count(0, $this->dao->findAll());
		Assert::exception(function () {
			$this->crudManager->update(100, []);
		}, 'Kappa\Doctrine\EntityNotFoundException');
	}
}

\run(new CrudManagerTest());
