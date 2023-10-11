# Criba

Criba is a PHP implementation of the criteria pattern. It facilitates searching and sorting records by offering a class based API that defines certain filters so they can latter be applied.

## Install

```bash
git clone https://github.com/hawara-es/criba.git

composer install
```

## Open a debug session

```bash
vendor/bin/psysh
```

## Usage

```php
$criteria = new Criba\Criteria(
    new Criba\Filter(
        new Criba\Condition('name', 'in', ['España', 'Inglaterra']),
    ),
    new Criba\OrderBy(['name' => 'asc'])
);
```

## Run the tests

```bash
vendor/bin/pest
```

## Fix the code style

```bash
vendor/bin/pint
```