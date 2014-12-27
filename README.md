# Kappa\DoctrineHelpers

Collection of classes for better work with Doctrine

## Requirements

* PHP 5.4 or higher
* [Doctrine 2](http://www.doctrine-project.org/)
* [Kdyby\Doctrine](https://github.com/Kdyby/Doctrine)
* [Nette Framework](http://nette.org/)

## Installation:

The best way to install Kappa\DoctrineHelpers is using [Composer](https://getcomposer.com)

```shell
$ composer require kappa/doctrine-helpers:@dev
```

## Usages

### Hydartors

#### ArrayHydrator

```php
$data = [
	'defaultValue' => 'Test'
];
$entity = new User();
$entity->setName('John')
	->setEmail('john@example.com');
$arrayHydrator->hydrate($data, $entity);
echo $data['defaultValue'] // returns "Test"
echo $data['name'] // returns "John"
echo $data['email'] // returns "john@example.com"
```

`ArrayHydrator::hydrate()` accept two required arguments and two next optional arguments. First optional argument define ignored columns
and second argument you can use for conversion Doctrine collections to array

#### EntityHydrator

```php
$data = [
	'name' => 'John',
	'email' => 'john@example.com'
];
$entity = new User();
$entity->setNick('johnyX');
$entityHydrator->hydrate($entity, $data);
```

`EntityHydrator::hydrate()` accept two required arguments and one optional. Option argument can be array of ignored keys in input array.

For columns defined as Doctrine collections can be used `add()` method of the collection 


