[![Build Status](https://travis-ci.org/Kappa-org/DoctrineHelpers.svg)](https://travis-ci.org/Kappa-org/DoctrineHelpers)

# Kappa\DoctrineHelpers 

Collection of classes for better work with Doctrine

## Requirements

* PHP 5.4 or higher
* [Doctrine 2](http://www.doctrine-project.org/)
* [Nette Framework](http://nette.org/)
* [Kdyby\Doctrine](https://github.com/Kdyby/Doctrine)

## Installation:

The best way to install Kappa\DoctrineHelpers is using [Composer](https://getcomposer.com)

```shell
$ composer require kappa/doctrine-helpers:@dev
```

## Usages

### ArrayHydrator

```php
$data = [
	'defaultValue' => 'Test'
];
$entity = new User();
$entity->setName('John')
	->setEmail('john@example.com');
$arrayHydrator->hydrate($data, $entity);
echo $data['defaultValue'] // returns "Test"
echo $data['name']; // returns "John"
echo $data['email']; // returns "john@example.com"
```

`ArrayHydrator::hydrate()` requires two arguments and two optionals arguments. First optional argument define ignored columns
and second argument you can use for conversion Doctrine collections to array

### EntityHydrator

```php
$data = [
	'name' => 'John',
	'email' => 'john@example.com'
];
$entity = new User();
$entity->setNick('johnyX');
$entityHydrator->hydrate($entity, $data);
```
`EntityHydrator::hydrate()` requires two arguments and one optional. Option argument can be array of ignored keys in input array.

For columns defined as Doctrine collections will be used `add()` method of the collection

`EntityHydrator` contains support for automatically insert entity into relations by defined `targetEntity`

#### Example

**Database**

id  | parent_id | name
----|-----------|-------
1   | NULL      | Johm

**Usages**
```php
$entity = new Entity();
$data = [
	'name' => 'John junior',
	'parent' => 1
];
$entityHydrator->hydrate($entity, $data);
var_dump($entity->getParent()); // returns entity with id 1 and name John
```
If is column defined as relations and value is integer, will be automatically converted to entity from database and inserted into new entity


### FormItemsCreator

```php
$form = new Form();
$form->addSelect('parent', 'Parent item: ', $this->formItemsCreator->create('\UserEntity', new GetAll());
// or
$user = new User();
$form->addSelect('parent', 'Parent item: ', $this->formItemsCreator->create($user, new GetAll());
```

```php
$this->formItemsCreator->create('\UserEntity', new GetAll());
``` 

use default columns `id` and `title` and create array like this

```php
$array = [
	'1' => 'John'
];
```

You can change default columns via config
```yaml
doctrineHelpers:
	forms:
		items:
			identifierColumn: id
			valueColumn: name
```

or as a third and fourth argument 
```php
$this->formItemsCreator->create('\UserEntity', new GetAll(), 'name', 'id');
```

Third argument is `valueColumn` and last argument is `identifierColumn`
