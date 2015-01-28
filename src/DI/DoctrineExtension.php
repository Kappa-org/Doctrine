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
 *
 * @package Kappa\Doctrine\DI
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DoctrineExtension extends CompilerExtension
{
	private $defaultConfig = [
		'forms' => [
			'items' => [
				'identifierColumn' => 'id',
				'valueColumn' => 'title'
			]
		],
		'identity' => false
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$this->processForms($config['forms']);
		$this->processConverter();
		if ($config['identity']) {
			$this->processIdentity();
		}
	}

	private function processIdentity()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition('nette.userStorage')
			->setClass('Kappa\Doctrine\Http\UserStorage');
	}

	private function processForms($config)
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('formItemsCreator'))
			->setClass('Kappa\Doctrine\Forms\FormItemsCreator', [
				'@doctrine.default.entityManager',
				$config['items']
			]);
	}

	private function processConverter()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('converter'))
			->setClass('Kappa\Doctrine\Converters\Converter');
	}
}
