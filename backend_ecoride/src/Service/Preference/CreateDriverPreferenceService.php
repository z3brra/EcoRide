<?php

namespace App\Service\Preference;

use App\Entity\CustomDriverPreference;
use App\Repository\CustomDriverPreferenceRepository;
use App\DTO\Preference\{
    CustomDriverPreferenceDTO,
    CustomDriverPreferenceReadDTO
};

use App\Entity\User;

use App\Service\ValidationService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateDriverPreferenceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CustomDriverPreferenceRepository $customRepository,
        private ValidationService $validationService,
    ) {}

    public function createCustomPref(
        User $user,
        CustomDriverPreferenceDTO $createCustomDTO
    ): CustomDriverPreferenceReadDTO
    {
        if ($createCustomDTO->isEmpty()) {
            throw new BadRequestHttpException("No data to create");
        }

        $this->validationService->validate($createCustomDTO, ['create']);

        $customPref = new CustomDriverPreference();
        $customPref->setOwner($user)
                   ->setLabel($createCustomDTO->label)
                   ->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($customPref);
        $this->entityManager->flush();

        return CustomDriverPreferenceReadDTO::fromEntity($customPref);
    }
}

?>