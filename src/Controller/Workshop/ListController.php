<?php

declare(strict_types=1);

namespace App\Controller\Workshop;

use App\Pagination\WorkshopCollectionFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/workshops', name: 'list_workshop', methods: ['GET'])]
final class ListController
{
    public function __construct(
        private readonly WorkshopCollectionFactory $workshopCollectionFactory,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(Request $request): Response
    {
        // since 6.3 https://symfony.com/doc/current/controller.html#mapping-the-whole-query-string
        $workshopCollection = $this->workshopCollectionFactory->create(
            $request->query->getInt('page', 1),
            $request->query->getInt('size', 10)
        );

        $serializedWorkshopCollection = $this->serializer->serialize($workshopCollection, $request->getRequestFormat());

        return new Response($serializedWorkshopCollection, Response::HTTP_OK);
    }
}
