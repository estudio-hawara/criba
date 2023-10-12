# Criba

Criba is a PHP implementation of the **criteria pattern**. It facilitates searching, sorting and paginating records by offering a class based API that defines certain filters so they can latter be applied.

## Introduction

The main idea behind the criteria pattern is to separate the definition of a certain filter, sorting or pagination definition from its application in an actual database query. This is particularly interesting when you are writting code following at least some of the domain driven design approaches.

### Context

I started this repository when I was following some [CodelyTV](https://codely.com) courses. This video in particular may be helpful to add some context:

- [How to handle millions of records in a database with domain driven development](https://www.youtube.com/watch?v=B9xKFuFRbws)? (Spanish)

## Define a criteria

A criteria is the combination of a filter, an order by clause and a pagination criteria.

```php
class Criteria
{
    public readonly Filter $filter;
    public readonly OrderBy $orderBy;
    public readonly Page $page;
    // ...
}
```

See: [Criteria.php](src/Criteria.php).

### An example with orders

Let's say you have an order class with a property amount and you want to implement a view that shows the latest 10 orders with an amount greater or equal than 1000 (of whatever currency). First, you implement the criteria without any concern about how it will be translated to a particular database engine.

```php
new Criba\Criteria(
    new Criba\Filter(
        new Criba\Condition('amount', '>=', 1000),
    ),
    new Criba\OrderBy(['order_date' => 'asc']),
    new Criba\Page(10, 0)
);
```

This example shows the simpler possible example of a filter, where it only consists in a single condition.

### Negate a condition

As you may imagine from the example above, conditions require a field name, an operator and a value. But they also accept an additional `negate` boolean that can be used to invert them.

```php
// we added the name of the arguments here for readability
new Criba\Condition(field: 'amount', operator: '<', value: 1000, negate: true);

// or in short form
new Criba\Condition('amount', '<', 1000, true);
```

See: [Condition.php](src/Condition.php).

### Comparing fields

In order to compare two fields, rather than a field with a value, you can use comparisons, which can be used wherever conditions can be used.

```php
new Criba\Criteria(
    new Criba\Filter(
        new Criba\Comparison('amount_due', '>', 'amount_paid'),
    ),
);
```

See: [Comparison.php](src/Comparison.php).

### Joining conditions and comparisons

To define more complex filters, conditions and comparisons can be joined with either `and` or `or`.

```php
new Criba\Criteria(
    new Criba\Filter(
        new Criba\Condition('amount', '>=', 1000),
        'and',
        new Criba\Comparison('amount_due', '>', 'amount_paid'),
    ),
    new Criba\OrderBy(['order_date' => 'asc']),
    new Criba\Page(10, 0)
);
```

This example shows how filters support three arguments:

- the 1st and 3rd argument, must be conditions, comparisons or other filters (see more examples below),
- the 2nd argument bust be one of the strings: `and` or `or`.

### Going crazy with recursion

The most powerful thing of this package is the possibility of using other filters in the place of conditions and comparisons.

```php
$confirmed = new Criba\Filter(
    new Criba\Comparison('amount_due', '>', 'amount_paid'),
    'or',
    new Criba\Condition('status', '=', 'confirmed'),
    parentheses: true
),

new Criba\Criteria(
    new Criba\Filter(
        new Criba\Condition('amount', '>=', 1000),
        'and',
        $confirmed
    ),
    new Criba\OrderBy(['order_date' => 'asc']),
    new Criba\Page(10, 0)
);
```

See: [Filter.php](src/Filter.php).

### Pushing a criteria to a specification

What's nice about the class based API is that if we were happy with that implementation and we wanted to push it to our code so that it represents part of our business logic, we could simply wrap it around a class.

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

At this moment, only an Eloquent implementation has been written.

See: [CountryRepository.php](tests/Fixtures/Eloquent/Repository/CountryRepository.php) to check how this fits when used with the repository pattern (in a domain driven design example).

## Install

### Via Composer (recommended for production)

To be able to use the **Criba** classes in your PHP project, the easiest and recommended way is to require it via Composer.

```bash
composer require hawara/criba
```

### Via GitHub (recommended for development)

If instead you want to modify this package itself (for instance, to send a pull request), you are encouraged to clone this repository using Git.

```bash
git clone https://github.com/hawara-es/criba.git

composer install

# optionally, you can install the package in production mode
# but you won't be able to run the test suite
composer install --no-dev
```

### Dependencies

This package does only have two types of dependencies:

- the **suggested**, which are not actual dependencies as you may not use them,
- the **development** ones, which you will only need if you are going to participate in the package development.

In practice, it means that if you install it in production mode, no dependencies will be installed.

## Open a debug session

The [PsySH](https://psysh.org) debugger is installed as a Composer dependency, what means that you can quickly open an interactive PHP session to test a criteria by running:

```bash
vendor/bin/psysh
```

The session will autoload the project namespaces, so you can directly run `new Criba\Criteria` without even adding a **use** statement.

## Run the tests

The [Pest](https://pestphp.com) test engine helps tests being beautiful, readable, quick and are integrated into a GitHub workflow, so every pull request will run them.

```bash
vendor/bin/pest
```

## Fix the code style

The [Pint](https://laravel.com/docs/10.x/pint) code style fixer has been set up to facilitate following Laravel's suggested coding styles.

```bash
vendor/bin/pint
```