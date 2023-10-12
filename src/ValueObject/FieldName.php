<?php

declare(strict_types=1);

namespace Criba\ValueObject;

use Criba\ValueObject\Definition\ValueObjectAbstract;
use Criba\ValueObject\Exception\FieldNameIsNotValidException;

class FieldName extends ValueObjectAbstract
{
    public function __construct(
        private string $field
    ) {
        if (! preg_match('/^[_a-zA-Z][_a-zA-Z0-9]*$/', $field)) {
            throw new FieldNameIsNotValidException();
        }
    }

    public function get(): string
    {
        return $this->field;
    }
}
