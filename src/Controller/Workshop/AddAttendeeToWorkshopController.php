<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Entity\Attendee;
use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/workshops/{identifier}/attendees/add/{attendee_identifier}', name: 'add_attendee_to_workshop', methods: ['POST'])]
#[OA\Post(tags: ['Workshop'])]
class AddAttendeeToWorkshopController
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
        $workshop->addAttendee($attendee);

        $this->workshopRepository->save($workshop);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
