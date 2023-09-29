<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Entity\Attendee;
use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/workshops/{identifier}/attendees/remove/{attendee_identifier}', name: 'remove_attendee_from_workshop', methods: ['POST'])]
class RemoveAttendeeFromWorkshopController
{
    public function __construct(
        private readonly WorkshopRepository $workshopRepository,
    ) {
    }

    public function __invoke(
        Request $request,
        Workshop $workshop,
        #[MapEntity(Attendee::class, expr: 'repository.findOneByIdentifier(attendee_identifier)')]
        Attendee $attendee
    ) {
        $workshop->removeAttendee($attendee);

        $this->workshopRepository->save($workshop);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
