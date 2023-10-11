<?php

declare(strict_types=1);

namespace Criba;

use Criba\Exception\FilterIsInvalidException;

class Filter
{
    const SUPPORTED_JOINS = ['and', 'or'];

    public function __construct(
        public readonly Condition|Comparison|Filter|null $condition = null,
        public readonly ?string $join = null,
        public readonly Condition|Comparison|Filter|null $extraCondition = null,
        public readonly bool $parentheses = false
    ) {
        if (is_null($join) && ! is_null($extraCondition)) {
            throw new FilterIsInvalidException('If a filter has a join, it must also have two conditions');
        }

        if (! is_null($join) && is_null($extraCondition)) {
            throw new FilterIsInvalidException('If a filter has two conditions, it must also have a join');
        }

        if (! is_null($join) && ! in_array($join, self::SUPPORTED_JOINS)) {
            throw new FilterIsInvalidException('Unsupported join type for a filter');
        }
    }
}
