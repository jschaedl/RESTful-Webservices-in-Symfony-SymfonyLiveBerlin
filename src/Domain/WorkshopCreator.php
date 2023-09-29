<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\CreateWorkshopModel;
use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Ramsey\Uuid\Uuid;

final class WorkshopCreator
{
    public function __construct(
        private readonly WorkshopRepository $workshopRepository,
    ) {
    }

    public function create(CreateWorkshopModel $createWorkshopModel): Workshop
    {
        $newWorkshop = new Workshop(
            Uuid::uuid4()->toString(),
            $createWorkshopModel->title,
            \DateTimeImmutable::createFromFormat('Y-m-d', $createWorkshopModel->workshopDate),
        );

        $this->workshopRepository->save($newWorkshop);

        return $newWorkshop;
    }
}
