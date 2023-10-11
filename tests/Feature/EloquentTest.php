<?php

use Tests\Fixtures\Eloquent\Model\Country;

it('can use eloquent in tests', function () {
    $this->bootEloquent();

    Country::create([
        'id' => '34d033f5-9925-4fed-995a-9acf866e150d',
        'name' => 'España',
        'alpha2' => 'ES',
    ]);

    expect(Country::whereName('España')->first()->alpha2)->toBe('ES');
});
