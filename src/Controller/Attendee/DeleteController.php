<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Domain\AttendeeRemover;
use App\Entity\Attendee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/attendees/{identifier}', name: 'delete_attendee', methods: ['DELETE'])]
class DeleteController
{
    public function __construct(
        private readonly AttendeeRemover $attendeeRemover,
    ) {
    }

    public function __invoke(Request $request, Attendee $attendee)
    {
        $this->attendeeRemover->remove($attendee);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
