<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="globals")
 */
class GlobalEntity extends BaseEntity implements IEntity
{
	use Identifier;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $column;

	/**
	 * @ORM\Column(type="string")
	 */
	public $pub_column;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $column_s;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $column_ies;

	/**
	 * @ORM\OneToOne(targetEntity="GlobalEntity")
	 */
	protected $toOne;

	/**
	 * @ORM\OneToOne(targetEntity="GlobalEntity")
	 */
	public $pub_toOne;

	/**
	 * @ORM\OneToOne(targetEntity="GlobalEntity", mappedBy="")
	 */
	protected $toOne_s;

	/**
	 * @ORM\OneToOne(targetEntity="GlobalEntity", mappedBy="")
	 */
	protected $toOne_ies;

	/**
	 * @ORM\OneToMany(targetEntity="GlobalEntity", mappedBy="")
	 */
	protected $toMany_s;

	/**
	 * @ORM\OneToMany(targetEntity="GlobalEntity", mappedBy="")
	 */
	public $pub_toMany_s;

	/**
	 * @ORM\OneToMany(targetEntity="GlobalEntity", mappedBy="")
	 */
	protected $toMany_ies;

	public function __construct()
	{
		$this->toMany_ies = new ArrayCollection();
		$this->toMany_s = new ArrayCollection();
		$this->pub_toMany_s = new ArrayCollection();
	}

	public function getToMany_s()
	{
		return $this->toMany_s;
	}

	public function getToMany_ies()
	{
		return $this->toMany_ies;
	}
}
