<?php

declare(strict_types=1);

namespace Criba;

use Criba\Exception\PageIsInvalidException;

class Page
{
    public function __construct(
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
        if (! is_null($limit) && $limit < 0) {
            throw new PageIsInvalidException('The page limit must be a positive integer');
        }

        if (! is_null($offset) && $offset < 0) {
            throw new PageIsInvalidException('The page offset must be a positive integer');
        }
    }
}
