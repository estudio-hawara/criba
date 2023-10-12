# Criba

Criba is a PHP implementation of the **criteria pattern**. It facilitates searching, sorting and paginating records by offering a class based API that defines certain filters so they can latter be applied.

## Introduction

The main idea behind the criteria pattern is to separate the definition of a certain filter, sorting or pagination definition from its application in an actual database query. This is particularly interesting when you are writting code following at least some of the domain driven design approaches.

### Context

I started this repository when I was following some [CodelyTV](https://codely.com) courses. This video in particular may be helpful to add some context:

- [How to handle millions of records in a database with domain driven development](https://www.youtube.com/watch?v=B9xKFuFRbws)? (Spanish)

## Define a criteria

### An example with orders

Let's say you have an order class with a property amount and you want to implement a view that shows the latest 10 orders with an amount greater or equal than 1000 (of whatever currency). First, you implement the criteria without any concern about how it will be translated to a particular database engine.

```php
$criteria = new Criba\Criteria(
    new Criba\Filter(
        new Criba\Condition('amount', '>=', 1000),
    ),
    new Criba\OrderBy(['order_date' => 'asc']),
    new Criba\Page(10, 0)
);
```

### Pushing a criteria to a specification

What's nice about the previous example is that if we were happy with that implementation and we wanted to push it to our code so that it represents part of our business logic, we could simply wrap it around a class.

```php
class TopOrdersCriteria extends Criba\Criteria
{
    public function __construct()
    {
        parent::__construct(
            new Criba\Filter(
                new Criba\Condition('amount', '>=', 1000),
            ),
            new Criba\OrderBy(['order_date' => 'asc']),
            new Criba\Page(10, 0)
        );
    }
}
```

- [The specification design pattern](https://www.youtube.com/watch?v=u_87ME-7JVc) (Spanish)

## Use a criteria definition

The thing about using a criteria is that it's different for every database. This package offers an already working class that translates any criteria to an Eloquent query.

```php
use Criba\Infrastructure\Eloquent\EloquentCriteriaBuilder;

$builder = new EloquentCriteriaBuilder(App\Models\Order::class);

/** @var  Illuminate\Database\Schema\Builder  $query */
$query = $builder->query(new TopOrdersCriteria);

$query->get()->all();
```

## Install

For the moment, this isn't a production ready package. So it isn't still available via Composer. If you still want to test it, clone it and use Composer to install its dependencies.

```bash
git clone https://github.com/hawara-es/criba.git

composer install
```

## Open a debug session

```bash
vendor/bin/psysh
```

## Run the tests

```bash
vendor/bin/pest
```

## Fix the code style

```bash
vendor/bin/pint
```