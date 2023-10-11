<?php

use Criba\Condition;
use Criba\Criteria;
use Criba\Filter;
use Criba\Infrastructure\Eloquent\EloquentCriteriaBuilder;
use Criba\OrderBy;
use Tests\Fixtures\Eloquent\Model\CountryModel;
use Tests\Fixtures\Eloquent\Repository\CountryRepository;

beforeEach(function () {
    $this->bootEloquent();

    CountryModel::create([
        'id' => '0699ac76-6fba-4f70-9465-7c7fb5ce050e',
        'name' => 'Afganistán',
        'alpha2' => 'AF',
    ]);

    CountryModel::create([
        'id' => '34d033f5-9925-4fed-995a-9acf866e150d',
        'name' => 'España',
        'alpha2' => 'ES',
    ]);

    $model = CountryModel::class;
    $builder = new EloquentCriteriaBuilder($model);
    $this->repository = new CountryRepository($builder);
});

it('can be used with an empty criteria', function () {
    $countries = $this->repository->search(new Criteria());

    expect($countries->count())->toBe(CountryModel::count());
});

it('can be used with a condition that uses the equal operator (`=`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('alpha2', '=', 'ES'),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('España');
});

it('can be used with a negated condition that uses the equal operator (`=`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('alpha2', '=', 'ES', negate: true),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('Afganistán');
});

it('can be used with a condition that uses the different than operator (`!=`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('id', '!=', '34d033f5-9925-4fed-995a-9acf866e150d'),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('Afganistán');
});

it('can be used with a negated condition that uses the different than operator (`!=`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('alpha2', '!=', 'ES', negate: true),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('España');
});

it('can be used with a condition that uses the multiple selection operator (`in`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('id', 'in', [
                '34d033f5-9925-4fed-995a-9acf866e150d',
                '0699ac76-6fba-4f70-9465-7c7fb5ce050e',
            ]),
        ),
    ));

    expect($countries->count())->toBe(2);
});

it('can be used with a negated condition that uses the multiple selection operator (`in`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('id', 'in', [
                '34d033f5-9925-4fed-995a-9acf866e150d',
            ], negate: true),
        ),
    ));

    expect($countries->count())->toBe(1);
});

it('can be used with a condition that uses the greater than operator (`>`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('name', '>', 'Afganistán'),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('España');
});

it('can be used with a negated condition that uses the greater than operator (`>`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('name', '>', 'Afganistán', negate: true),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('Afganistán');
});

it('can be used with a condition that uses the lower or equal than operator (`<=`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('name', '>', 'Afganistán', negate: true),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('Afganistán');
});

it('can be used with a condition that uses the lower than operator (`<`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('name', '<', 'España'),
        ),
    ));

    expect($countries->count())->toBe(1);
    expect($countries->countries[0]->name)->toBe('Afganistán');
});

it('can be used with a negated condition that uses the lower than operator (`<`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('name', '<', 'Afganistán', negate: true),
        ),
    ));

    expect($countries->count())->toBe(2);
});

it('can be used with a condition that uses the greated or equal than operator (`>=`)', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('name', '>=', 'Afganistán'),
        ),
    ));

    expect($countries->count())->toBe(2);
});

it('can be used with a filter that contains another filter', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('id', '=', '34d033f5-9925-4fed-995a-9acf866e150d'),
            'or',
            new Filter(
                new Condition('id', '=', '0699ac76-6fba-4f70-9465-7c7fb5ce050e'),
            ),
        ),
    ));

    expect($countries->count())->toBe(2);
});

it('can be used with a contradictory criteria and will return an empty set', function () {
    $countries = $this->repository->search(new Criteria(
        new Filter(
            new Condition('id', '=', '34d033f5-9925-4fed-995a-9acf866e150d'),
            'and',
            new Condition('id', '=', '0699ac76-6fba-4f70-9465-7c7fb5ce050e'),
        ),
    ));

    expect($countries->count())->toBe(0);
});

it('can be used without conditions to sort items', function () {
    $countries = $this->repository->search(new Criteria(
        orderBy: new OrderBy(['alpha2' => 'asc']),
    ));

    expect($countries->count())->toBe(2);
    expect($countries->countries[0]->name)->toBe('Afganistán');
    expect($countries->countries[1]->name)->toBe('España');
});
