<?php

declare(strict_types=1);

namespace Criba;

class Criteria
{
    public readonly Filter $filter;
    public readonly OrderBy $orderBy;
    public readonly Page $page;

    public function __construct(
        ?Filter $filter = null,
        ?OrderBy $orderBy = null,
        ?Page $page = null
    ) {
        if (! $filter) {
            $filter = new Filter();
        }

        if (! $orderBy) {
            $orderBy = new OrderBy();
        }

        if (! $page) {
            $page = new Page();
        }

        $this->filter = $filter;
        $this->orderBy = $orderBy;
        $this->page = $page;
    }
}
