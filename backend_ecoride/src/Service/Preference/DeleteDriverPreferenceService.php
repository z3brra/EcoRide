<?php

namespace App\Service\Preference;

use App\DTO\Preference\CustomDriverPreferenceDTO;

use App\Service\Access\AccessControlService;
use App\Service\ValidationService;

use App\Repository\CustomDriverPreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteDriverPreferenceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CustomDriverPreferenceRepository $customPrefRepository,
        private AccessControlService $accessControl,
        private ValidationService $validationService,
    ) {}

    public function delete(array $deleteCustomPrefDTO): array
    {
        $this->validationService->validateEach($deleteCustomPrefDTO, ['delete']);

        $deleteUuids = [];

        foreach ($deleteCustomPrefDTO as $deleteDTO) {
            if (!$deleteDTO instanceof CustomDriverPreferenceDTO || $deleteDTO->uuid === null) {
                continue;
            }

            $entity = $this->customPrefRepository->findOneByUuid($deleteDTO->uuid);
            if (!$entity) {
                throw new NotFoundHttpException("Custom preference not found or does not exist");
            }

            $this->accessControl->denyUnlessOwnerByRelation($entity);

            $this->entityManager->remove($entity);
            $deleteUuids[] = $deleteDTO->uuid;
        }
        $this->entityManager->flush();

        return $deleteUuids;
    }
}


?>
