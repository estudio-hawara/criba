<?php

declare(strict_types=1);

namespace Tests\Fixtures\Eloquent\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

class CountryTable
{
    public function __construct(
        private Builder $builder
    ) {
        //
    }

    public function up(): void
    {
        $this->builder->create('countries', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->unique();
            $table->char('alpha2', 2);
            $table->string('description')->nullable();
        });
    }

    public function down(): void
    {
        $this->builder->drop('countries');
    }
}
