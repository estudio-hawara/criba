<?php

declare(strict_types=1);

namespace Criba;

use Criba\Exception\ConditionIsInvalidException;
use Criba\ValueObject\FieldName;

class Condition
{
    const SUPPORTED_OPERATORS = ['=', '!=', '>', '<', '>=', '<=', 'in', 'regexp'];

    public function __construct(
        public readonly string $field,
        public readonly string $operator,
        public readonly string|bool|int|float|array|null $value,
        public readonly bool $negate = false,
        public readonly bool $parentheses = false
    ) {
        $this->assertIsValidFieldName($field);
        $this->assertIsValidOperator($operator);
        $this->assertIsValidValueForTheOperator($value, $operator);
    }

    private function assertIsValidFieldName(string $field)
    {
        try {
            new FieldName($field);
        } catch (\Exception $e) {
            throw new ConditionIsInvalidException('The field name is not valid');
        }
    }

    private function assertIsValidOperator(string $operator)
    {
        if (! in_array($operator, self::SUPPORTED_OPERATORS)) {
            throw new ConditionIsInvalidException('The operator is not valid');
        }
    }

    private function assertIsValidValueForTheOperator(string|bool|int|float|array|null $value, string $operator)
    {
        if (is_array($value) && $operator !== 'in') {
            throw new ConditionIsInvalidException('Only the `in` operator supports arrays');
        }

        if (! is_array($value) && $operator === 'in') {
            throw new ConditionIsInvalidException('The `in` operator only works with arrays');
        }

        if (is_array($value) && count(array_filter($value, fn ($v) => ! is_string($v) && ! is_bool($v) && ! is_int($v) && ! is_float($v) && ! is_null($v)))) {
            throw new ConditionIsInvalidException('The array of options for an `in` condition can only contain primitive values');
        }

        if (! is_string($value) && $operator === 'regexp') {
            throw new ConditionIsInvalidException('The value must be of type string if the operator is `regexp`');
        }
    }
}
