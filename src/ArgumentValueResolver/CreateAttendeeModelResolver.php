<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\Domain\Model\CreateAttendeeModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateAttendeeModelResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return CreateAttendeeModel::class === $argument->getType() && 'POST' === $request->getMethod();
    }

    /**
     * @return iterable<CreateAttendeeModel>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // it returns an iterable, because the controller method argument could be variadic

        yield $this->serializer->deserialize(
            $request->getContent(),
            CreateAttendeeModel::class,
            $request->getRequestFormat(),
        );
    }
}
