<?php

declare(strict_types=1);

namespace App\Domain;

use App\Entity\Workshop;
use App\Repository\WorkshopRepository;

final class WorkshopRemover
{
    public function __construct(
        private readonly WorkshopRepository $workshopRepository,
    ) {
    }

    public function remove(Workshop $workshop): void
    {
        $this->workshopRepository->delete($workshop);
    }
}
