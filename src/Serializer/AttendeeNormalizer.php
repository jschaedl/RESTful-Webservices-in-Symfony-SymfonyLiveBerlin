<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Attendee;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AttendeeNormalizer implements NormalizerInterface
{
    public function __construct(
        // see: https://github.com/symfony/maker-bundle/issues/1252
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Attendee;
    }

    /**
     * @param Attendee $object
     *
     * @return array|string
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $customContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['id'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($object, $format, $context) => $object->getFirstname().' '.$object->getLastname(),
        ];

        $context = array_merge($context, $customContext);

        $data = $this->normalizer->normalize($object, $format, $context);

        if (\is_array($data)) {
            $data['_links']['self']['href'] = $this->urlGenerator->generate('read_workshop', [
                'identifier' => $object->getIdentifier(),
            ]);

            $data['_links']['collection']['href'] = $this->urlGenerator->generate('list_workshop');
        }

        return $data;
    }

    // see: https://github.com/symfony/symfony-docs/issues/18042
    public function getSupportedTypes(?string $format): array
    {
        return [
            Attendee::class => true,
        ];
    }
}
