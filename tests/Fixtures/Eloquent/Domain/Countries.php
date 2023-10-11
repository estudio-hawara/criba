<?php

declare(strict_types=1);

namespace Tests\Fixtures\Eloquent\Domain;

class Countries
{
    public readonly array $countries;

    public function __construct(Country ...$countries)
    {
        $this->countries = $countries ?? [];
    }

    public function count(): int
    {
        return count($this->countries);
    }
}
