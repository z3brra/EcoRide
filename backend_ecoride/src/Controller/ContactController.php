<?php

namespace App\Controller;

use App\DTO\Contact\ContactRequestDTO;
use App\Service\Contact\ContactService;

use App\Exception\McsException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[Route('/api/contact', name: 'app_api_contact_')]
final class ContactController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    #[Route('', name: 'send', methods: 'POST')]
    public function send(
        Request $request,
        ContactService $contactService
    ): JsonResponse {
        try {
            try {
                $contactRequestDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: ContactRequestDTO::class,
                    format: 'json'
                );
            } catch (\Throwable $e) {
                throw new BadRequestHttpException('Invalid JSON format.');
            }

            $contactService->send($contactRequestDTO);

            return new JsonResponse(
                data: ['message' => 'Message has been received.'],
                status: JsonResponse::HTTP_OK
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (McsException $e) {
            return new JsonResponse(
                data: [
                    'error' => 'Mail sending failed',
                    'message' => $e->getMessage(),
                    'mcsCode' => $e->getCodeValue(),
                    'requestId' => $e->getRequestId(),
                ],
                status: JsonResponse::HTTP_BAD_GATEWAY
            );
        } catch (\Throwable $e) {
            return new JsonResponse(
                data: ['error' => 'An internal server error has occurred'],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}

?>