<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Workshop;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class WorkshopNormalizer implements NormalizerInterface
{
    public function __construct(
        // see: https://github.com/symfony/maker-bundle/issues/1252
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Workshop;
    }

    /**
     * @param Workshop $object
     *
     * @return array|string
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $customContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['id'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($object, $format, $context) => $object->getTitle(),
        ];

        $context = array_merge($context, $customContext);

        return $this->normalizer->normalize($object, $format, $context);
    }

    // see: https://github.com/symfony/symfony-docs/issues/18042
    public function getSupportedTypes(?string $format): array
    {
        return [
            Workshop::class => true,
        ];
    }
}
