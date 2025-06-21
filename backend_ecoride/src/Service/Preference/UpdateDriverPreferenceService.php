<?php

namespace App\Service\Preference; 

use App\Entity\User;

use App\Entity\CustomDriverPreference;
use App\DTO\Preference\{
    AggregatedPrefDTO,
    AggregatedPrefReadDTO,
    CustomDriverPreferenceDTO
};

use App\Repository\CustomDriverPreferenceRepository;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\{
    BadRequestHttpException,
    NotFoundHttpException
};

use DateTimeImmutable;

class UpdateDriverPreferenceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidationService $validationService,
        private CustomDriverPreferenceRepository $customPrefRepository,
    ) {}

    public function update(User $user, AggregatedPrefDTO $updateAggregatedPrefDTO): AggregatedPrefReadDTO
    {
        $this->validationService->validate($updateAggregatedPrefDTO, ['update']);

        if ($updateAggregatedPrefDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to update");
        }

        if ($updateAggregatedPrefDTO->fixedPref !== null) {
            $fixedPref = $user->getFixedDriverPreference();
            if (!$fixedPref) {
                throw new BadRequestHttpException("Fixed driver preferences not found");
            }

            if ($updateAggregatedPrefDTO->fixedPref->animals !== null) {
                $fixedPref->setAnimals($updateAggregatedPrefDTO->fixedPref->animals);
            }
            if ($updateAggregatedPrefDTO->fixedPref->smoke !== null) {
                $fixedPref->setSmoke($updateAggregatedPrefDTO->fixedPref->smoke);
            }

            $fixedPref->setUpdatedAt(new DateTimeImmutable());
        }

        if (!empty($updateAggregatedPrefDTO->customPref)) {
            foreach ($updateAggregatedPrefDTO->customPref as $updateCustomDTO) {
                if ($updateCustomDTO->uuid === null) {
                    continue;
                }

                /** @var CustomDriverPreference $entity */
                $entity = $this->customPrefRepository->findOneBy(['uuid' => $updateCustomDTO->uuid]);
                if (!$entity || $entity->getOwner() !== $user) {
                    throw new NotFoundHttpException("Custom preference not found or does not exist");
                }

                $entity->setLabel($updateCustomDTO->label);
                $entity->setUpdatedAt(new DateTimeImmutable());
            }
        }
        $this->entityManager->flush();

        return AggregatedPrefReadDTO::fromEntity($user);
    }
}

?>