<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Http;

use Kdyby\Doctrine\EntityManager;
use Nette\Http\Session;

/**
 * Class UserStorage
 *
 * @package Kappa\Doctrine\Http
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class UserStorage extends \Nette\Http\UserStorage
{
	/** @var EntityManager */
	private $entityManager;

	/** @var string */
	private $className;

	/**
	 * @param Session $session
	 * @param EntityManager $entityManager
	 * @param string $className
	 */
	public function __construct(Session $session, EntityManager $entityManager, $className)
	{
		parent::__construct($session);
		$this->entityManager = $entityManager;
		$this->className = $className;
	}


	/**
	 * @return \Nette\Security\IIdentity|NULL|void
	 */
	public function getIdentity()
	{
		$dao = $this->entityManager->getRepository($this->className);
		$identity = parent::getIdentity();
		if (!$identity) {
			return null;
		}
		$entity = $dao->find($identity->getId());

		return $entity;
	}
}
