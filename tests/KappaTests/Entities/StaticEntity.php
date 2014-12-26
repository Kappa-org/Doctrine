<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="statics")
 */
class StaticEntity extends BaseEntity
{
	use Identifier;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $string;

	/**
	 * @ORM\Column(type="integer", name="age")
	 * @var int
	 */
	protected $int;

	/**
	 * @ORM\Column(type="integer")
	 */
	public $public;

	/**
	 * @param $string
	 */
	public function setString($string)
	{
		$this->string = $string;
	}

	/**
	 * @return string
	 */
	public function getString()
	{
		return $this->string;
	}
}
