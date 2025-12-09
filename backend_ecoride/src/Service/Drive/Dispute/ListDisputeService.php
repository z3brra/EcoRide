<?php

namespace App\Service\Drive\Dispute;

use App\DTO\Credit\CreditReadDTO;

use App\Repository\{
    CreditRepository,
    DriveRepository,
    UserRepository
};
use Symfony\Component\HttpKernel\Exception\{
    NotFoundHttpException
};

class ListDisputeService
{
    public function __construct(
        private CreditRepository $creditRepository,
        private DriveRepository $driveRepository,
        private UserRepository $userRepository,
    ) {}

    public function listDisputePaginated(int $page, int $limit): array
    {
        $result  = $this->creditRepository->findDisputePaginated($page, $limit);
        /** @var \App\Document\Credit[] $credits */
        $credits = $result['data'];

        if (!$credits) {
            return [
                'data'        => [],
                'total'       => $result['total'],
                'totalPages'  => $result['totalPages'],
                'currentPage' => $result['currentPage'],
                'perPage'     => $result['perPage'],
            ];
        }

        $driveUuids       = [];
        $participantUuids = [];

        foreach ($credits as $credit) {
            $driveUuids[]       = $credit->getDriveUuid();
            $participantUuids[] = $credit->getParticipantUuid();
        }

        $driveUuids       = array_values(array_unique($driveUuids));
        $participantUuids = array_values(array_unique($participantUuids));

        $drives = $this->driveRepository->findBy(['uuid' => $driveUuids]);
        $participants  = $this->userRepository->findBy(['uuid' => $participantUuids]);


        $drivesByUuid = [];
        foreach ($drives as $drive) {
            $drivesByUuid[$drive->getUuid()] = $drive;
        }
        $participantsByUuid = [];
        foreach ($participants as $participant) {
            $participantsByUuid[$participant->getUuid()] = $participant;
        }


        $creditDTOs = [];
        foreach ($credits as $credit) {
            $driveUuid       = $credit->getDriveUuid();
            $participantUuid = $credit->getParticipantUuid();

            $drive = $drivesByUuid[$driveUuid] ?? null;
            $participant  = $participantsByUuid[$participantUuid] ?? null;

            if (!$drive) {
                throw new NotFoundHttpException("Drive not found for uuid $driveUuid");
            }
            if (!$participant) {
                throw new NotFoundHttpException("Participant not found for uuid $participantUuid");
            }

            $creditDTOs[] = CreditReadDTO::fromDocument($credit, $drive, $participant);
        }

        return [
            'data'        => $creditDTOs,
            'total'       => $result['total'],
            'totalPages'  => $result['totalPages'],
            'currentPage' => $result['currentPage'],
            'perPage'     => $result['perPage'],
        ];
    }
}
?>