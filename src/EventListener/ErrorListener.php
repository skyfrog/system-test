<?php

namespace App\EventListener;

use App\Exception\ApiError;
use App\Response\Error\ErrorResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ErrorListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($e = $event->getThrowable()) {
            if ($e instanceof ApiError) {
                $message = $e->getMessage();
                $context = $e->getContext();
            } else {
                $message = 'Internal error';
                $context = null;
                $this->logger->error($e);
            }

            $event->setResponse(
                new Response(
                    $this->serializer->serialize(new ErrorResponse($message, $context), 'json'),
                    Response::HTTP_BAD_REQUEST,
                    ['Content-type' => 'application/json']
                )
            );
        }
    }
}