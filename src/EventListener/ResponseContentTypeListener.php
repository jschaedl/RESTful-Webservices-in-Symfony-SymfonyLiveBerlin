<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::RESPONSE, method: 'onKernelResponse', priority: 8)]
final class ResponseContentTypeListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ('app.swagger_ui' === $request->get('_route')) {
            return;
        }

        $response->headers->set(
            'Content-Type',
            sprintf('%s; charset=utf-8', $request->getMimeType($request->getRequestFormat()))
        );
    }
}
