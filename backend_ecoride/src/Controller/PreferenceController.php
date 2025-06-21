<?php

namespace App\Controller;

use App\DTO\Preference\CustomDriverPreferenceDTO;
use App\Service\Preference\{
    CreateDriverPreferenceService,
    ReadDriverPreferenceService,

};

use App\Service\Access\AccessControlService;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
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
}



?>