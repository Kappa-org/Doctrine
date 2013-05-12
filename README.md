#Kappa\Doctrine

Basic classes for working with [Doctrine 2](http://www.doctrine-project.org/) and [Nette Framework](http://nette.org)

##Requirements:

* PHP 5.3.* or higher
* [Nette Framework](http://nette.org)
* [Doctrine 2](http://www.doctrine-project.org/)
* [Composer](http://getcomposer.org/)

##Installation

The best way to install Kappa/Doctrine is using Composer:

```bash
$ composer require kappa/doctrine:@dev
```

Add section doctrine into config and register extension

```yaml
doctrine:
	connection:
		driver: pdo_mysql
		charset: utf8
		port: 3306
		host: localhost
		dbname: testDB
		user: root
		password: root
	entities: %appDir%/Entity
```

```php
\Kappa\Doctrine\DI\DoctrineExtension::register($configurator);
```