<?php

namespace App\EventListener;

use App\Exception\PendingSettlementException;
use App\Security\Attribute\BypassSettlementLock;
use App\Service\Access\AccessControlService;
use App\Service\Settlement\SettlementLockService;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::CONTROLLER, priority: 0)]
class PendingSettlementListener
{
    public function __construct(
        private AccessControlService $accessControl,
        private SettlementLockService $lockService
    ) {}

    public function __invoke(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (!$this->accessControl->isLogged()) {
            return;
        }

        if ($this->accessControl->isBanned()) {
            return;
        }

        if ($this->accessControl->isEmployee()) {
            return;
        }

        $controller = $event->getController();
        if (is_array($controller)) {
            $reflection = new ReflectionMethod($controller[0], $controller[1]);
            $attributes = $reflection->getAttributes(BypassSettlementLock::class);
            if (!empty($attributes)) {
                return;
            }
        } else {
            return;
        }

        $user = $this->accessControl->getUser();
        $drive = $this->lockService->getBlockingDrive($user);

        // if ($this->lockService->hasBlocking($user)) {
        if ($drive !== null) {
            throw new PendingSettlementException($drive->getUuid());
        }
    }
}

?>