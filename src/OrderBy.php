<?php

declare(strict_types=1);

namespace Criba;

use Criba\Exception\OrderByIsInvalidException;
use Criba\ValueObject\FieldName;

class OrderBy
{
    const SUPPORTED_DIRECTIONS = ['asc', 'desc'];

    public readonly array $orders;

    public function __construct(array $orders = [])
    {
        $ordersByField = [];

        foreach ($orders as $field => $direction) {
            $this->assertIsValidField($field);
            $this->assertIsValidDirection($direction);

            $ordersByField[$field] = $direction;
        }

        $this->orders = $ordersByField;
    }

    private function assertIsValidField(string $field)
    {
        try {
            new FieldName($field);
        } catch (\Exception $e) {
            throw new OrderByIsInvalidException('One of the field names of the order by clause is not valid');
        }
    }

    private function assertIsValidDirection(string $direction)
    {
        if (! in_array($direction, self::SUPPORTED_DIRECTIONS)) {
            throw new OrderByIsInvalidException('An invalid value was specified for an order direction. Only `asc` and `desc` are supported');
        }
    }
}
