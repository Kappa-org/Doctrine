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

### EntityArrayConverter::entityToArray()

```php
$entity = new User();
$entity->setName('John')
	->setEmail('john@example.com');
$data = $entityArrayConverter->entityToArray($entity);
echo $data['name']; // returns "John"
echo $data['email']; // returns "john@example.com"
```

`EntityArrayConverter::entityToArray()` requires one argument and three optionals arguments. First optional argument define ignored columns, 
second argument you can use for conversion Doctrine collections to array and by last argument you can defined entity transformation.

**Transformation example:**

```php
$article = new Article();
$user = new User();
$user->setName('John');
$user->getId(); // returns 10

$article->setTitle('Example article');
$article->setUser($user);
$data = $this->entityArrayConverter->entityToArray($article);
$data_transform = $this->entityArrayConverter->entityToArray($article, [], false, ['user' => 'id']);
var_dump($data['user']); // returns object of User entity
var_dump($data_transform['user']); // return 10 (id of User entity)
```

### EntityArrayConverter::arrayToEntity()

```php
$data = [
	'name' => 'John',
	'email' => 'john@example.com'
];
$entity = new User();
$entity->setNick('johnyX');
$entityArrayConverter->arrayToEntity($entity, $data);
// or
$entity = $entityArrayConverter->arrayToEntity('Example\Namespace\User', $data);
```
`EntityArrayConverter::arrayToEntity()` requires two arguments and one optional. Option argument can be array of ignored keys in input array.

For columns defined as Doctrine collections will be used `add()` method of the collection

`EntityArrayConverter::arrayToEntity()` contains support for automatically insert entity into relations by defined `targetEntity`

#### Example

**Database**

id  | parent_id | name
----|-----------|-------
1   | NULL      | Johm

**Usages**
```php
$data = [
	'name' => 'John junior',
	'parent' => 1
];
$entity = $entityArrayConverter->arrayToEntity('Example\Namespace\User', $data);
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
