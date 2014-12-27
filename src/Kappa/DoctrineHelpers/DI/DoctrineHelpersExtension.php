<?php
/**
 * This file is part of the Kappa\DoctrineHelpers package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineHelpers\DI;

use Nette\DI\CompilerExtension;

/**
 * Class DoctrineHelpersExtension
 *
 * @package Kappa\DoctrineHelpers\DI
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DoctrineHelpersExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$this->processReflections();
		$this->processHydrators();
	}

	private function processHydrators()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('entityHydrator'))
			->setClass('Kappa\DoctrineHelpers\Hydrators\EntityHydrator');
		$builder->addDefinition($this->prefix('arrayHydrator'))
			->setClass('Kappa\DoctrineHelpers\Hydrators\ArrayHydrator');
	}

	private function processReflections()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('entityReflectionFactory'))
			->setClass('Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory');
	}
}
