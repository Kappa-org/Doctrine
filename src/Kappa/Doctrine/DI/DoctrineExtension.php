<?php
/**
 * DoctrineExtension.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 12.5.13
 *
 * @package Kappa\Doctrine
 */

namespace Kappa\Doctrine\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

/**
 * Class DoctrineExtension
 *
 * @package Kappa\Doctrine\DI
 */
class DoctrineExtension extends CompilerExtension
{
	/** @var array */
	private $defaultParams = array(
		'connection' => array(
			'driver' => 'pdo_mysql',
			'charset' => 'utf8',
			'port' => '3306',
		),
		'entities' => '%appDir%/Entity',
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultParams);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('sqlLogger'))
			->setFactory('Kappa\Doctrine\Diagnostics\ConnectionPanel::register');

		$builder->addDefinition($this->prefix('mappingStrategy'))
			->setClass('Doctrine\ORM\Mapping\UnderscoreNamingStrategy');

		$builder->addDefinition($this->prefix('entityManagerConfig'))
			->setClass('Doctrine\ORM\Configuration')
			->setFactory('Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration', arraY($config['entities']), true)
			->addSetup('setSQLLogger', array('@doctrine.sqlLogger'))
			->addSetup('setProxyDir', array('%appDir%/../temp/proxy'))
			->addSetup('setNamingStrategy', array('@doctrine.mappingStrategy'));

		$builder->addDefinition($this->prefix('entityManager'))
			->setClass('Doctrine\ORM\EntityManager')
			->setFactory('Doctrine\ORM\EntityManager::create', array($config['connection'], '@doctrine.entityManagerConfig'));
	}

	/**
	 * @param \Nette\Configurator $config
	 */
	public static function register(Configurator $config)
	{
		$config->onCompile[] = function (Configurator $config, Compiler $compiler) {
			$compiler->addExtension('doctrine', new DoctrineExtension());
		};
	}
}
