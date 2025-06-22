<?php

namespace App\Service\Drive;

use App\Entity\Drive;
use App\Repository\DriveRepository;
use App\Service\Access\AccessControlService;
use App\Service\StringHelper;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteDriveService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriveRepository $driveRepository,
        private AccessControlService $accessControl,
        private StringHelper $stringHelper,
    ) {}

    public function delete(string $identifier): void
    {
        if ($this->stringHelper->isUuid($identifier)) {
            $drive = $this->driveRepository->findOneByUuid($identifier);
        } else {
            $drive = $this->driveRepository->findOneByReference($identifier);
        }
        if (!$drive instanceof Drive) {
            throw new NotFoundHttpException("Drive not found or does not exist");
        }

        $this->accessControl->denyUnlessOwnerByRelation($drive);

        $this->entityManager->remove($drive);
        $this->entityManager->flush();
    }
}


?>