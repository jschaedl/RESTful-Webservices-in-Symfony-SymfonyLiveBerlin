<?php

declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\Domain\Model\CreateAttendeeModel;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateAttendeeModelResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    // public function supports(Request $request, ArgumentMetadata $argument): bool
    // {
    //    return CreateAttendeeModel::class === $argument->getType() && 'POST' === $request->getMethod();
    // }

    /**
     * @return iterable<CreateAttendeeModel>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!(CreateAttendeeModel::class === $argument->getType() && 'POST' === $request->getMethod())) {
            return [];
        }

        // it returns an iterable, because the controller method argument could be variadic

        $model = $this->serializer->deserialize(
            $request->getContent(),
            CreateAttendeeModel::class,
            $request->getRequestFormat(),
        );

        $validationErrors = $this->validator->validate($model);

        if (\count($validationErrors) > 0) {
            throw new ValidationFailedException($model, $validationErrors);
        }

        yield $model;
    }
}
