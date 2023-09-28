<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Model\UpdateAttendeeModel;
use App\Entity\Attendee;
use App\Repository\AttendeeRepository;

final class AttendeeUpdater
{
    public function __construct(
        private readonly AttendeeRepository $attendeeRepository,
    ) {
    }

    public function update(Attendee $attendee, UpdateAttendeeModel $createAttendeeModel): Attendee
    {
        if ($firstname = $createAttendeeModel->firstname) {
            $attendee->changeFirstname($firstname);
        }

        if ($lastname = $createAttendeeModel->lastname) {
            $attendee->changeLastname($lastname);
        }

        if ($email = $createAttendeeModel->email) {
            $attendee->changeEmail($email);
        }

        $this->attendeeRepository->save($attendee);

        return $attendee;
    }
}
