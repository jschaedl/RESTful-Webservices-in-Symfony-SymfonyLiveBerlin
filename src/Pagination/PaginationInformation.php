<?php

namespace App\Pagination;

class PaginationInformation
{
    public function __construct(
        public readonly ?int $page = 1,
        public readonly ?int $size = 10,
    ) {
    }
}
