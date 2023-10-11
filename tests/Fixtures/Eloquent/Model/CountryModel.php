<?php

declare(strict_types=1);

namespace Tests\Fixtures\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    protected $table = 'countries';

    protected $keyType = 'string';

    public $timestamps = false;

    protected static function booted(): void
    {
        static::unguard();
    }
}
