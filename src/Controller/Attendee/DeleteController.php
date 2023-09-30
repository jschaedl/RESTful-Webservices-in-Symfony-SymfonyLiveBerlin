<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Domain\AttendeeRemover;
use App\Entity\Attendee;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/attendees/{identifier}', name: 'delete_attendee', methods: ['DELETE'])]
#[OA\Delete(tags: ['Attendee'])]
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
