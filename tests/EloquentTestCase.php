<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;
use Tests\Fixtures\Eloquent\Migration\CountryTable;

class EloquentTestCase extends TestCase
{
    public function bootEloquent()
    {
        $capsule = new Capsule;

        $connection = [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ];

        $capsule->addConnection($connection);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->runMigrations($capsule->schema());
    }

    private function runMigrations(Builder $builder)
    {
        (new CountryTable($builder))->up();
    }
}
