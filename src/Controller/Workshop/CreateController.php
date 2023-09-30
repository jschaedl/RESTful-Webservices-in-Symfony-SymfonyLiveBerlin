<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Domain\Model\CreateWorkshopModel;
use App\Domain\WorkshopCreator;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('ROLE_USER')]
#[Route('/workshops', name: 'create_workshop', methods: ['POST'])]
#[OA\Post(
    description: 'Creates an workshop.',
    summary: 'Creates an workshop.',
    requestBody: new OA\RequestBody(
        content: new Model(type: CreateWorkshopModel::class)
    ),
    tags: ['Workshop'],
    responses: [
        new OA\Response(
            response: 201,
            description: 'Returns the created workshop.'
        ),
    ],
)]
final class CreateController
{
    public function __construct(
        private readonly WorkshopCreator $workshopCreator,
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, CreateWorkshopModel $createWorkshopModel)
    {
        $createdWorkshop = $this->workshopCreator->create($createWorkshopModel);

        $serializedCreatedWorkshop = $this->serializer->serialize($createdWorkshop, $request->getRequestFormat());

        return new Response($serializedCreatedWorkshop, Response::HTTP_CREATED, [
            'Location' => $this->urlGenerator->generate('read_workshop', [
                'identifier' => $createdWorkshop->getIdentifier(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}
