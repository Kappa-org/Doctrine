<?php
/**
 * This file is part of the Kappa/Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\DI;

use Kdyby\Annotations\DI\AnnotationsExtension;
use Kdyby\Console\DI\ConsoleExtension;
use Kdyby\Doctrine\DI\OrmExtension;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;

/**
 * Class DoctrineExtension
 * @package Kappa\Doctrine\DI
 */
class DoctrineExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$compiler = $this->compiler;
		$compiler->addExtension('console', new ConsoleExtension());
    	$compiler->addExtension('events', new EventsExtension());
    	$compiler->addExtension('annotations', new AnnotationsExtension());
    	$compiler->addExtension('doctrine', new OrmExtension());
	}
}