<?php

namespace App\EventListener;

use App\Exception\DriveTransitionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class DriveTransitionExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof DriveTransitionException) {
            return;
        }

        $responseBody = json_decode($exception->getMessage(), true);

        if ($responseBody === null) {
            $responseBody = ['errors' => [$exception->getMessage()]];
        }

        $response = new JsonResponse(
            data: $responseBody,
            status: $exception->getStatusCode()
        );

        $event->setResponse($response);
    }
}

?>