<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\Http;

use Kdyby\Doctrine\EntityManager;
use Nette\Http\Session;

/**
 * Class UserStorage
 *
 * @package Kappa\DoctrineHelpers\Http
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class UserStorage extends \Nette\Http\UserStorage
{
	/** @var \Kdyby\Doctrine\EntityDao */
	private $identities;
	/**
	 * @param Session $session
	 * @param EntityManager $entityManager
	 */
	public function __construct(Session $session, EntityManager $entityManager)
	{
		parent::__construct($session);
		$this->identities = $entityManager->getDao('Nette\Security\IIdentity');
	}


	/**
	 * @return \Nette\Security\IIdentity|NULL|void
	 */
	public function getIdentity()
	{
		$identity = parent::getIdentity();
		if (!$identity) {
			return null;
		}
		$entity = $this->identities->find($identity->getId());

		return $entity;
	}
}
