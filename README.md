[![Build Status](https://travis-ci.org/Kappa-org/Doctrine.svg)](https://travis-ci.org/Kappa-org/Doctrine)

# Kappa\Doctrine

Collection of classes for better work with Doctrine

## Requirements

* PHP 5.4 or higher
* [Doctrine 2](http://www.doctrine-project.org/)
* [Nette Framework](http://nette.org/)
* [Kdyby\Doctrine](https://github.com/Kdyby/Doctrine)

## Installation:

The best way to install Kappa\Doctrine is using [Composer](https://getcomposer.com)

```shell
$ composer require kappa/doctrine:@dev
```

## Usages

### Converter::entityToArray()

Method `entityToArray` requires entity object and returns `Kappa\Doctrine\Converters\EntityToArrayConverter`.

* `setIgnoreList(array)` - set list of items which you can ignore *(ignore list and white list can be combined)*
* `setWhiteList(array)` - set list of items which you can transform *(ignore list and white list can be combined)*
* `addColumnCallback(column name, callable)` - set custom callback for transform concrete column
* `addRelationCallback(column name, callable) - set custom callback for transform concrete column with relations
* `convert()` - returns generated array

**Example:**

```php
<?php
$user = new User("Joe");
$user->setParent(new User("Joe senior"))
	->setAge(50);
	->setPrivate("private");
$array = $converter->entityToArray($user)
	->setIgnoreList(["private"])
	->addColumnCallback("age", function ($age) { return $age / 10; })
	->addRelationCallback("parent", function(User $parent) { return $parent->getName(); })
	->convert();
echo $array['name']; // print Joe
echo $array['parent']; // print Joe senior
echo $array['age']; // print 5
```

### EntityArrayConverter::arrayToEntity()

Method `arrayToEntity` requires two argument. First argument can be entity object or entity class name and returns 
`Kappa\Doctrine\Converters\ArrayToEntityConverter`.

* `setIgnoreList(array)` - set list of items which you can ignore *(ignore list and white list can be combined)*
* `setWhiteList(array)` - set list of items which you can transform *(ignore list and white list can be combined)*
* `convert()` - returns generated array

**Example:**

```php
$data = [
	'name' => 'Joe';
	'private' => 'text';
];
$entity = $converter->arrayToEntity('User', $data)
	->setIgnoreList(['private'])
	->convert();
echo $entity->getName(); // print Joe 
```

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
doctrine:
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
