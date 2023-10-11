<?php

declare(strict_types=1);

namespace Tests\Fixtures\Eloquent\Domain;

class Country
{
    public function __construct
    (
        public readonly string $id,
        public readonly string $name,
        public readonly string $alpha2
    ) {
        //
    }
}