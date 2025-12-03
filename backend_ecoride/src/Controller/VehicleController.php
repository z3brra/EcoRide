<?php

namespace App\Controller;

use App\DTO\Vehicle\{VehicleDTO};

use App\Service\Vehicle\{
    CreateVehicleService,
    DeleteVehicleService,
    ReadVehicleService,
    UpdateVehicleService,
    ListVehiclePaginatedService,
    ListAllVehicleService,
};

use App\Service\Access\AccessControlService;

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
        private AccessControlService $accessControl,
    ) {}

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        CreateVehicleService $createVehicleService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->getUser();

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
        } catch (ConflictHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_CONFLICT
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/all', name: 'list_all', methods: 'GET')]
    public function listAll(
        ListAllVehicleService $listAllVehicleService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->getUser();

            // var_dump('here');
            $vehiclesDTO = $listAllVehicleService->listAllVehicle($user);
            // var_dump($vehiclesDTO);
            $responseData = $this->serializer->serialize(
                data: $vehiclesDTO,
                format: 'json',
                context: ['groups' => ['vehicle:list']]
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

    #[Route('/{uuid}', name: 'update', methods: 'PUT')]
    public function update(
        string $uuid,
        Request $request,
        UpdateVehicleService $updateVehicleService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->getUser();

            try {
                $vehicleUpdateDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: VehicleDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }
            $vehicleReadDTO = $updateVehicleService->updateVehicle($user, $uuid, $vehicleUpdateDTO);
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
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } 
    }

    #[Route('/{uuid}', name: 'delete', methods: 'DELETE')]
    public function delete(
        string $uuid,
        DeleteVehicleService $deleteVehicleService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $deleteVehicleService->deleteVehicle($uuid);

            return new JsonResponse(
                data: ['message' => 'Vehicle successfully deleted'],
                status: JsonResponse::HTTP_OK,
            );

        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_FORBIDDEN
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

    #[Route('', name: 'list', methods: 'GET')]
    public function list(
        Request $request,
        ListVehiclePaginatedService $listVehicleService
    ): JsonResponse {
        try {
            $page = max(1, (int) $request->query->get('page', 1));
            $limit = max(1, (int) $request->query->get('limit', 10));

            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->getUser();

            $vehiclePaginated = $listVehicleService->listVehiclePaginatedByUser($user, $page, $limit);

            $responseData = $this->serializer->serialize(
                data: $vehiclePaginated,
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
        } 
        // catch (\Exception $e) {
        //     return new JsonResponse(
        //         data: ['error' => "An internal server error as occured"],
        //         status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        //     );
        // }
    }


}



?>