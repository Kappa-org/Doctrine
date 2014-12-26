<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="relations")
 */
class RelationsEntity extends BaseEntity
{
	use Identifier;

	/**
	 * @ORM\OneToOne(targetEntity="RelationsEntity")
	 */
	protected $oto;

	/**
	 * @ORM\ManyToOne(targetEntity="RelationsEntity")
	 */
	protected $mto;

	/**
	 * @ORM\OneToMany(targetEntity="RelationsEntity", mappedBy="")
	 */
	protected $otms;

	/**
	 * @ORM\ManyToMany(targetEntity="RelationsEntity")
	 */
	protected $mtmies;

	/**
	 * @ORM\ManyToMany(targetEntity="RelationsEntity")
	 */
	public $public_mto;

	public function __construct()
	{
		$this->otms = new ArrayCollection();
		$this->mtmies = new ArrayCollection();
	}
}
