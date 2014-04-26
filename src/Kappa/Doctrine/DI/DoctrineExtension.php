<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\DI;

use Nette\DI\CompilerExtension;

/**
 * Class DoctrineExtension
 * @package Kappa\Doctrine\DI
 */
class DoctrineExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('entityManipulator'))
			->setClass('Kappa\Doctrine\Helpers\EntityManipulator');

		$builder->addDefinition($this->prefix('arrayHydrator'))
			->setClass('Kappa\Doctrine\Hydrators\ArrayHydrator');
	}
}