<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Pagination\AttendeeCollectionFactory;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/attendees', name: 'list_attendee', methods: ['GET'])]
#[OA\Get(
    description: 'Returns a paginated collection of attendees.',
    summary: 'Returns a paginated collection of attendees.',
    tags: ['Attendee'],
    parameters: [
        new OA\Parameter(
            name: 'page',
            description: 'The field to specify the current page.',
            in: 'query',
            schema: new OA\Schema(type: 'integer')
        ),
        new OA\Parameter(
            name: 'size',
            description: 'The field to specify the current page size.',
            in: 'query',
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Returns a list of attendees.',
            content: [
                new OA\MediaType(mediaType: 'application/json'),
                new OA\MediaType(mediaType: 'application/hal+json'),
                new OA\MediaType(mediaType: 'text/xml'),
            ]
        ),
    ]
)]
final class ListController
{
    public function __construct(
        private AttendeeCollectionFactory $attendeeCollectionFactory,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        // since 6.3 https://symfony.com/doc/current/controller.html#mapping-the-whole-query-string
        $attendeeCollection = $this->attendeeCollectionFactory->create(
            $request->query->getInt('page', 1),
            $request->query->getInt('size', 10)
        );

        $serializedAttendeeCollection = $this->serializer->serialize($attendeeCollection, $request->getRequestFormat());

        return new Response($serializedAttendeeCollection, Response::HTTP_OK);
    }
}
