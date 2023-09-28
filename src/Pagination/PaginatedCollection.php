<?php

declare(strict_types=1);

namespace App\Pagination;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class PaginatedCollection
{
    public readonly array $items;
    public readonly int $total;
    public readonly int $count;

    #[SerializedName('_links')]
    public ?array $links;

    public function __construct(\Iterator $items, int $total)
    {
        $this->items = iterator_to_array($items);
        $this->total = $total;
        $this->count = \count($this->items);
    }

    public function addLink(string $rel, string $href): self
    {
        $this->links[$rel]['href'] = $href;

        return $this;
    }
}
