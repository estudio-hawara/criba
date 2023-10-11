<?php

declare(strict_types=1);

namespace Tests\Fixtures\Eloquent\Repository;

use Criba\Criteria;
use Criba\Infrastructure\Eloquent\EloquentCriteriaBuilder;
use Tests\Fixtures\Eloquent\Domain\Countries;
use Tests\Fixtures\Eloquent\Domain\Country;
use Tests\Fixtures\Eloquent\Model\CountryModel;

class CountryRepository
{
    public function __construct(
        private EloquentCriteriaBuilder $builder
    ) {
        //
    }

    public function search(Criteria $criteria): Countries
    {
        $query = $this->builder->query($criteria);

        /** @var  Country[]  $countries */
        $countries = $query->get()
            ->map(fn ($record) => $this->map($record))
            ->all();

        return new Countries(...$countries);
    }

    private function map(CountryModel $record): Country
    {
        return new Country($record->id, $record->name, $record->alpha2);
    }
}