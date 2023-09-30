<?php

declare(strict_types=1);

namespace App\Controller\Attendee;

use App\Domain\AttendeeCreator;
use App\Domain\Model\CreateAttendeeModel;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/attendees', name: 'create_attendee', methods: ['POST'])]
#[OA\Post(
    description: 'Creates an attendee.',
    summary: 'Creates an attendee.',
    requestBody: new OA\RequestBody(
        content: new Model(type: CreateAttendeeModel::class)
    ),
    tags: ['Attendee'],
    responses: [
        new OA\Response(
            response: 201,
            description: 'Returns the created attendee.'
        ),
    ],
)]
final class CreateController
{
    public function __construct(
        private AttendeeCreator $attendeeCreator,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, CreateAttendeeModel $createAttendeeModel)
    {
        $createdAttendee = $this->attendeeCreator->create($createAttendeeModel);

        $serializedCreatedAttendee = $this->serializer->serialize($createdAttendee, $request->getRequestFormat());

        return new Response($serializedCreatedAttendee, Response::HTTP_CREATED, [
            'Location' => $this->urlGenerator->generate('read_attendee', [
                'identifier' => $createdAttendee->getIdentifier(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}
