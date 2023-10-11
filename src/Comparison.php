<?php

declare(strict_types=1);

namespace Criba;

use Criba\Exception\ComparisonIsInvalidException;
use Criba\ValueObject\FieldName;

class Comparison
{
    const SUPPORTED_OPERATORS = ['=', '!=', '>', '<', '>=', '<='];

    public function __construct(
        public readonly string $field,
        public readonly string $operator,
        public readonly string $otherField
    ) {
        $this->assertIsValidFieldName($field);
        $this->assertIsValidOperator($operator);
        $this->assertIsValidFieldName($otherField);
    }

    private function assertIsValidFieldName(string $field)
    {
        try {
            new FieldName($field);
        } catch (\Exception $e) {
            throw new ComparisonIsInvalidException('The field name is not valid');
        }
    }

    private function assertIsValidOperator(string $operator)
    {
        if (! in_array($operator, self::SUPPORTED_OPERATORS)) {
            throw new ComparisonIsInvalidException('The operator is not valid');
        }
    }
}
