<?php

namespace App\EventListener;

use App\Entity\Drive;
use App\Enum\DriveStatusEnum;
use App\Service\Access\AccessControlService;

use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Attribute\AsGuardListener;
use Symfony\Component\Workflow\TransitionBlocker;

class DriveWorkflowGuardListener
{
    public function __construct(
        private AccessControlService $accessControl
    ) {}

    #[AsGuardListener(workflow: 'drive', transition: 'join')]
    public function guardJoin(GuardEvent $event): void
    {
        /** @var Drive $drive */
        $drive = $event->getSubject();

        if ($this->accessControl->isOwnerByEntityRelation($drive)) {
            $transitionBlocker = new TransitionBlocker('Owner can not join hiw own drive', 'owner');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getStatusEnum() !== DriveStatusEnum::OPEN) {
            $transitionBlocker = new TransitionBlocker('Drive must be open', 'status');
            $event->addTransitionBlocker($transitionBlocker);
        }

        $user = $this->accessControl->getUser();

        if ($user->getCredits() < $drive->getPrice()) {
            $transitionBlocker = new TransitionBlocker('Insuficient credits', 'credits');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getParticipants()->contains($user)) {
            $transitionBlocker = new TransitionBlocker('Already registered', 'duplicate');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getAvailableSeats() <= 0) {
            $transitionBlocker = new TransitionBlocker('No more seats', 'capacity');
            $event->addTransitionBlocker($transitionBlocker);
        }
    }

    #[AsGuardListener(workflow: 'drive', transition: 'leave')]
    public function guardLeave(GuardEvent $event): void
    {
        /** @var Drive $drive */
        $drive = $event->getSubject();

        if ($this->accessControl->isOwnerByEntityRelation($drive)) {
            $transitionBlocker = new TransitionBlocker('Owner can not leave his own drive', 'owner');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getStatusEnum() !== DriveStatusEnum::OPEN) {
            $transitionBlocker = new TransitionBlocker('Drive must be open', 'status');
            $event->addTransitionBlocker($transitionBlocker);
        }

        $user = $this->accessControl->getUser();
        if (!$drive->getParticipants()->contains($user)) {
            $transitionBlocker = new TransitionBlocker('User is not participant', 'not_participant');
            $event->addTransitionBlocker($transitionBlocker);
        }
    }

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

        if ($drive->getStatusEnum() !== DriveStatusEnum::OPEN) {
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
            $transitionBlocker = new TransitionBlocker('Only the owner can finish the drive', 'owner');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getStatusEnum() !== DriveStatusEnum::IN_PROGRESS) {
            $transitionBlocker = new TransitionBlocker('Drive must be in progess', 'status');
            $event->addTransitionBlocker($transitionBlocker);
        }
    }

    #[AsGuardListener(workflow: 'drive', transition: 'cancel')]
    public function guardCancel(GuardEvent $event): void
    {
        /** @var Drive $drive */
        $drive = $event->getSubject();

        if (!$this->accessControl->isOwnerByEntityRelation($drive)) {
            $transitionBlocker = new TransitionBlocker('Only the owner can finish the drive', 'owner');
            $event->addTransitionBlocker($transitionBlocker);
        }

        if ($drive->getStatusEnum() !== DriveStatusEnum::OPEN) {
            $transitionBlocker = new TransitionBlocker('Drive must be open', 'status');
            $event->addTransitionBlocker($transitionBlocker);
        }
    }
}

?>