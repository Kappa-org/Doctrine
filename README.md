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
* `addField(column name, value)` - set custom value for concrete field
* `addFieldCallback(column name, callable)` - set custom callback for concrete field
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
	->addField("age", 99)
	->addFieldCallback("parent", function(User $parent) { return $parent->getName(); })
	->convert();
echo $array['name']; // print Joe
echo $array['parent']; // print Joe senior
echo $array['age']; // print 99
```

### Converter::arrayToEntity()

Method `arrayToEntity` requires two argument. First argument can be entity object or entity class name and returns 
`Kappa\Doctrine\Converters\ArrayToEntityConverter`.

* `setIgnoreList(array)` - set list of items which you can ignore *(ignore list and white list can be combined)*
* `setWhiteList(array)` - set list of items which you can transform *(ignore list and white list can be combined)*
* `addItemCallback(column name, callable)` - set custom callback for concrete array item
* `convert()` - returns generated array

**Example:**

```php
$data = [
	'name' => 'Joe',
	'age' => 50,
	'sex' => 'male',
	'parent' => 1,
	'private' => 'text',
];
$entity = $converter->arrayToEntity('User', $data)
	->setIgnoreList(['private'])
	->setWhiteList(['age', 'name', 'private'])
	->addItem('sex', 'female')
	->addItemCallback('parent', function ($parent) {
		return $this->dao->find($parent);
	})
	->convert();
echo $entity->getName(); // print Joe
echo $entity->getSex(); // print female
$entity->getParent(); // returns instance of User
```

### CrudManager

Recommended way for create instance of `Kappa\Doctrine\Managers\CrudManager`
is use `Kappa\Managers\CrudManagerFactory`.

```php
<?php
$crudManager = $this->crudManagerFactory->create(new User());
// or
$crudManager = $this->crudManagerFactory->create('Some\Entity\User');
```

Method `create()` requires only one argument which it can be instance of entity or full namespace name. 

Created CrudManager contains three methods for basic works with entity. 

* `create(array)` - Create a new entity and fill with data
* `update(id, array)` - Find entity by `id` and fill with data
* `delete(id)` - Delete entity with `id`

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

### QueryExecutor

Time to time is needed run DQL query instead of manipulate with entity. Great way is build UPDATE (or DELETE) with `QueryBuilder`.

Is very useful to create a query object for such cases. In [Doctrine](http://www.doctrine-project.org/) and [Kdyby\Doctrine](https://github.com/Kdyby/Doctrine)
you can create `SELECT` query and run with `$this->repository->fetch(new QueryObject)` but `UPDATE` or `DELETE` query is not supported. `QueryExecutor`
is precisely for these situations.

**Example:**

```php
<?php

class ExecutableQuery implements Executable
{
	/**
	 * @param QueryBuilder $queryBuilder
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $queryBuilder)
	{
		$queryBuilder->update('KappaTests\Mocks\FormItemsEntity', 'r')
			->set('r.title', $queryBuilder->expr()->literal('UPDATED'))
			->where('r.id = ?0')
			->setParameters(1);

		return $queryBuilder;
	}
}

// and

$this->queryExecutor->execute(new ExecutableQuery());

