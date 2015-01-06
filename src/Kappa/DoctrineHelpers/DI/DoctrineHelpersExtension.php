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
	private $defaultConfig = [
		'forms' => [
			'items' => [
				'identifierColumn' => 'id',
				'valueColumn' => 'title'
			]
		]
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$this->processForms($config['forms']);
		$this->processReflections();
		$this->processConverters();
		$this->processIdentity();
	}

	private function processIdentity()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition('nette.userStorage')
			->setClass('Kappa\DoctrineHelpers\Http\UserStorage');
	}

	private function processForms($config)
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('formItemsCreator'))
			->setClass('Kappa\DoctrineHelpers\Forms\FormItemsCreator', [
				'@doctrine.default.entityManager',
				$this->prefix('@entityReflectionFactory'),
				$config['items']
			]);
	}

	private function processConverters()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('entityArrayConverter'))
			->setClass('Kappa\DoctrineHelpers\Converters\EntityArrayConverter');
	}

	private function processReflections()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('entityReflectionFactory'))
			->setClass('Kappa\DoctrineHelpers\Reflections\EntityReflectionFactory');
	}
}
