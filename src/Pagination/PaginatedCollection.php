<?php

declare(strict_types=1);

namespace App\Pagination;

final class PaginatedCollection
{
    public readonly array $items;
    public readonly int $total;
    public readonly int $count;

    public function __construct(\Iterator $items, int $total)
    {
        $this->items = iterator_to_array($items);
        $this->total = $total;
        $this->count = \count($this->items);
    }
}
