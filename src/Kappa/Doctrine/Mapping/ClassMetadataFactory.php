<?php
/**
 * This file is part of the Kappa\Doctrine package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Doctrine\Mapping;

use Doctrine\ORM\EntityManager;
use Kdyby\Doctrine\Configuration;
use Kdyby\Doctrine\Mapping\ClassMetadata;
use Kdyby\Doctrine\Mapping\RuntimeReflectionService;
use Kdyby\Doctrine\MissingClassException;

/**
 * Class ClassMetadataFactory
 * @package Kappa\Doctrine\Mapping
 */
class ClassMetadataFactory extends \Doctrine\ORM\Mapping\ClassMetadataFactory
{

	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var \Kdyby\Doctrine\Configuration */
	private $config;

	/**
	 * Enforce Nette\Reflection
	 */
	public function __construct()
	{
		$this->setReflectionService(new RuntimeReflectionService());
	}

	/**
	 * @param EntityManager $em
	 */
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
		$this->config = $em->getConfiguration();
		parent::setEntityManager($em);
	}

	protected function loadMetadata($name)
	{
		if ($this->config instanceof Configuration) {
			$name = $this->config->getTargetEntityClassName($name);
		}

		if (!class_exists($name)) {
			throw new MissingClassException("Metadata of class $name was not found, because the class is missing or cannot be autoloaded.");
		}

		return parent::loadMetadata($name);
	}

	/**
	 * @inheritdoc
	 * @return ClassMetadata
	 */
	public function getMetadataFor($className)
	{
		return parent::getMetadataFor($this->config->getTargetEntityClassName($className));
	}

	/**
	 * Creates a new ClassMetadata instance for the given class name.
	 *
	 * @param string $className
	 * @return ClassMetadata
	 */
	protected function newClassMetadataInstance($className)
	{
		return new ClassMetadata($className, $this->em->getConfiguration()->getNamingStrategy());
	}
}
