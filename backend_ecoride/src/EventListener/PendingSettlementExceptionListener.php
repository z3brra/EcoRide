<?php

namespace App\EventListener;

use App\Exception\PendingSettlementException;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class PendingSettlementExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof PendingSettlementException) {
            return;
        }

        $response = new JsonResponse(
            data: ['error' => $exception->getMessage()],
            status: $exception->getStatusCode()
        );

        $event->setResponse($response);
    }
}

?>
