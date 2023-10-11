<?php

declare(strict_types=1);

namespace Criba\ValueObject\Definition;

abstract class ValueObjectAbstract implements ValueObjectInterface
{
    public function get(): mixed
    {
        return null;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return get_class($other) === get_class($this) &&
            $other->get() === $this->get();
    }

    public function __toString(): string
    {
        return (string) $this->get();
    }
}
