<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Managers;

use Kappa\Doctrine\Converters\Converter;
use Kappa\Doctrine\EntityNotFoundException;
use Kdyby\Doctrine\EntityDao;
use Nette\Object;

/**
 * Class CrudManager
 *
 * @package Kappa\Doctrine\Managers
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class CrudManager extends Object
{
	/** @var EntityDao */
	private $dao;

	/** @var Converter */
	private $converter;

	/** @var string */
	private $class;

	/**
	 * @param EntityDao $dao
	 * @param Converter $converter
	 */
	public function __construct(EntityDao $dao, Converter $converter)
	{
		$this->dao = $dao;
		$this->converter = $converter;
		$this->class = $this->dao->getClassName();
	}

	/**
	 * @param array $data
	 */
	public function create(array $data)
	{
		$entity = $this->converter->arrayToEntity($this->class, $data)
			->convert();
		$this->dao->save($entity);
	}

	/**
	 * @param $id
	 * @param array $data
	 */
	public function update($id, array $data)
	{
		$entity = $this->dao->find($id);
		if (!$entity) {
			throw new EntityNotFoundException("Entity '{$this->class}' wtih id '{$id}' has not been found");
		}
		$entity = $this->converter->arrayToEntity($entity, $data)
			->convert();
		$this->dao->save($entity);
	}

	/**
	 * @param $id
	 */
	public function delete($id)
	{
		$entity = $this->dao->find($id);
		if (!$entity) {
			throw new EntityNotFoundException("Entity '{$this->class}' wtih id '{$id}' has not been found");
		}
		$this->dao->delete($entity);
	}
}
