<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\CreateAttendeeModel;
use App\Entity\Attendee;
use App\Repository\AttendeeRepository;
use Ramsey\Uuid\Uuid;

final class AttendeeCreator
{
    public function __construct(
        private readonly AttendeeRepository $attendeeRepository,
    ) {
    }

    public function create(CreateAttendeeModel $createAttendeeModel): Attendee
    {
        $newAttendee = new Attendee(
            Uuid::uuid4()->toString(),
            $createAttendeeModel->firstname,
            $createAttendeeModel->lastname,
            $createAttendeeModel->email,
        );

        $this->attendeeRepository->save($newAttendee);

        return $newAttendee;
    }
}
