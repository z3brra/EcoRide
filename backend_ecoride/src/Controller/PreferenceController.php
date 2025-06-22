<?php

namespace App\Controller;

use App\DTO\Preference\AggregatedPrefDTO;
use App\DTO\Preference\CustomDriverPreferenceDTO;
use App\Service\Preference\{
    CreateDriverPreferenceService,
    DeleteDriverPreferenceService,
    ReadDriverPreferenceService,
    UpdateDriverPreferenceService,
};

use App\Service\Access\AccessControlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException,
    AccessDeniedHttpException,
    BadRequestHttpException,
    ConflictHttpException
};

#[Route('/api/prefs', name: 'app_api_prefs_')]
final class PreferenceController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AccessControlService $accessControl,
    ) {}

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        CreateDriverPreferenceService $createPreferenceService,
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->accessControl->getUser();

            try {
                $customPrefCreateDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: CustomDriverPreferenceDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $customPrefReadDTO = $createPreferenceService->createCustomPref($user, $customPrefCreateDTO);
            $responseData = $this->serializer->serialize(
                data: $customPrefReadDTO,
                format: 'json',
                context: ['groups' => ['pref:read']]
            );

            return new JsonResponse(
                data: $responseData,
                status: JsonResponse::HTTP_CREATED,
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

    #[Route('', name: 'read', methods: 'GET')]
    public function read(
        ReadDriverPreferenceService $readPreferenceService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            $user = $this->accessControl->getUser();

            $preferenceReadDTO = $readPreferenceService->getCurrentUserPref($user);

            $responseData = $this->serializer->serialize(
                data: $preferenceReadDTO,
                format: 'json',
                context: ['groups' => ['pref:read']]
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

    #[Route('', name: 'update', methods: 'PUT')]
    public function update(
        Request $request,
        UpdateDriverPreferenceService $updatePrefService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyUnlessDriver();
            $this->accessControl->denyIfBanned();

            $user = $this->accessControl->getUser();

            try {
                $updatePrefDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: AggregatedPrefDTO::class,
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $readPrefDTO = $updatePrefService->update($user, $updatePrefDTO);

            $responseData = $this->serializer->serialize(
                data: $readPrefDTO,
                format: 'json',
                context: ['groups' => 'pref:read']
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
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('', name: 'delete', methods: 'DELETE')]
    public function delete(
        Request $request,
        DeleteDriverPreferenceService $deleteCustomPrefService
    ): JsonResponse {
        try {
            $this->accessControl->denyUnlessLogged();
            $this->accessControl->denyIfBanned();
            $this->accessControl->denyUnlessDriver();

            try {
                $deleteCustomPrefDTO = $this->serializer->deserialize(
                    data: $request->getContent(),
                    type: CustomDriverPreferenceDTO::class . "[]",
                    format: 'json'
                );
            } catch (\Exception $e) {
                throw new BadRequestHttpException("Invalid JSON format");
            }

            $deletedUuids = $deleteCustomPrefService->deleteMany($deleteCustomPrefDTO);
            $responseData = $this->serializer->serialize(
                data: [
                    'message' => 'Custom preferences are successfully deleted',
                    'deleted' => $deletedUuids
                ],
                format: 'json',
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
}



?>