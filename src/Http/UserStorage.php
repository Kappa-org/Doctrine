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
	private $entityManager;

	/**
	 * @param Session $session
	 * @param EntityManager $entityManager
	 */
	public function __construct(Session $session, EntityManager $entityManager)
	{
		parent::__construct($session);
		$this->entityManager = $entityManager;
	}


	/**
	 * @return \Nette\Security\IIdentity|NULL|void
	 */
	public function getIdentity()
	{
		$dao = $this->entityManager->getDao('Nette\Security\IIdentity');
		$identity = parent::getIdentity();
		if (!$identity) {
			return null;
		}
		$entity = $dao->find($identity->getId());

		return $entity;
	}
}
