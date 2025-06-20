<?php

namespace App\Controller;

use App\DTO\Vehicle\{VehicleDTO};

use App\Service\Vehicle\{
    CreateVehicleService,
    ReadVehicleService,
};

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use LogicException;
use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
    ConflictHttpException
};

#[Route('/api/vehicle', name: 'app_api_vehicle_')]
final class VehicleController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        CreateVehicleService $createVehicleService
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedHttpException("User is not authenticated");
            }

            try {
                $vehicleCreateDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: VehicleDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $vehicleReadDTO = $createVehicleService->createVehicle($user, $vehicleCreateDTO);
            $responseData = $this->serializer->serialize(
                data: $vehicleReadDTO,
                format: 'json',
                context: ['groups' => ['vehicle:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
            );
        } catch (LogicException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (ConflictHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_CONFLICT
            );
        }catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/{uuid}', name: 'read', methods: 'GET')]
    public function read(
        string $uuid,
        ReadVehicleService $readVehicleService
    ): JsonResponse {
        try {
            $vehicleReadDTO = $readVehicleService->getVehicle($uuid);

            $responseData = $this->serializer->serialize(
                data: $vehicleReadDTO,
                format: 'json',
                context: ['groups' => ['vehicle:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_OK,
                json: true
            );

        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => "An internal server error as occured"],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


}



?>