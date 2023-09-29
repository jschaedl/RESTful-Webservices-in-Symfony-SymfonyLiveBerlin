<?php

declare(strict_types=1);

namespace App\Domain;

use App\Entity\Attendee;
use App\Repository\AttendeeRepository;

final class AttendeeRemover
{
    public function __construct(
        private readonly AttendeeRepository $attendeeRepository,
    ) {
    }

    public function remove(Attendee $attendee): void
    {
        $this->attendeeRepository->delete($attendee);
    }
}
