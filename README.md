# This package will be removed! 

This package is now useless. All functions are implemented in [official doctrine package](https://github.com/doctrine) or 
in [Kdyby\Doctrine package](https://github.com/Kdyby/Doctrine).

Some useful functions of this packages will be moved into [Kappa\DoctrineHelpers package](https://github.com/Kappa-org/DoctrineHelpers), 
please use this package.




#Kappa\Doctrine

Basic classes for working with [Doctrine 2](http://www.doctrine-project.org/) and [Nette Framework](http://nette.org)

##Requirements:

* PHP 5.3.* or higher
* [Doctrine 2](http://www.doctrine-project.org/)
* [Kdyby/Doctrine](https://github.com/Kdyby/Doctrine)

[Installation instructions](https://github.com/Kdyby/Doctrine/blob/master/docs/en/index.md)

## Installation:

The best way to install Kappa\Doctrine is using [Composer](https://getcomposer.org)

```shell
$ composer require kappa/doctrine:@dev
```

## Usages

If you can use get dao by entity interface you can configure `classMetadataFactory` in doctrine
config section

```yaml
doctrine
	classMetadataFactory: Kappa\Doctrine\Mapping\ClassMetadataFactory
```
