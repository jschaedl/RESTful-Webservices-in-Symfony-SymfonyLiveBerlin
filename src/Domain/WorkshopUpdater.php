<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\UpdateWorkshopModel;
use App\Entity\Workshop;
use App\Repository\WorkshopRepository;

final class WorkshopUpdater
{
    public function __construct(
        private readonly WorkshopRepository $workshopRepository,
    ) {
    }

    public function update(Workshop $workshop, UpdateWorkshopModel $updateWorkshopModel): Workshop
    {
        if ($title = $updateWorkshopModel->title) {
            $workshop->changeTitle($title);
        }

        if ($workshopDate = $updateWorkshopModel->workshopDate) {
            $workshop->changeWorkshopDate(\DateTimeImmutable::createFromFormat('Y-m-d', $workshopDate));
        }

        $this->workshopRepository->save($workshop);

        return $workshop;
    }
}
