<?php

namespace App\EventListener;

use App\Entity\Drive;
use App\Enum\DriveStatus;
use App\Service\Access\AccessControlService;

use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Attribute\AsGuardListener;
use Symfony\Component\Workflow\TransitionBlocker;

class DriveWorkflowGuardListener
{
    public function __construct(
        private AccessControlService $accessControl
    ) {}

    #[AsGuardListener(workflow: 'drive', transition: 'start')]
    public function guardStart(GuardEvent $event): void
    {
        /** @var Drive $drive */
        $drive = $event->getSubject();

        if (!$this->accessControl->isOwnerByEntityRelation($drive)) {
            $transitionBlocker = new TransitionBlocker('Only the owner can start the drive', 'owner');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getParticipants()->isEmpty()) {
            $transitionBlocker = new TransitionBlocker('Cannot start with no participants', 'participants');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getStatusEnum() !== DriveStatus::OPEN) {
            $transitionBlocker = new TransitionBlocker('Drive must be open', 'status');
            $event->addTransitionBlocker($transitionBlocker);
        }
    }

    #[AsGuardListener(workflow: 'drive', transition: 'finish')]
    public function guardFinish(GuardEvent $event): void
    {
        /** @var Drive $drive */
        $drive = $event->getSubject();

        if (!$this->accessControl->isOwnerByEntityRelation($drive)) {
            $event->setBlocked(true, "Only the owner can finish the drive", 'owner');
            return;
        }

        if ($drive->getStatusEnum() !== DriveStatus::IN_PROGRESS) {
            $event->setBlocked(true, "Drive must be in progess", 'status');
            return;
        }
    }
}

?>