<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Tests;

use Doctrine\ORM\Mapping as ORM;
use Kappa\Doctrine\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tests")
 */
class ExampleEntity extends Entity
{
	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $email;
}