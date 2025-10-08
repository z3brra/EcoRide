<?php

namespace App\Service\Access;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccessControlService
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $authChecker,
    ) {}

    public function getUser(): User
    {
        $user = $this->getInternUser();
        if (!$user instanceof User) {
            throw new AccessDeniedHttpException("User is not authenticated");
        }
        return $user;
    }

    /** @internal Don't use outside because of not throwable */
    private function getInternUser(): ?UserInterface
    {
        return $this->tokenStorage->getToken()?->getUser();
    }

    public function isLogged(): bool
    {
        return $this->getInternUser() instanceof User;
    }

    public function isAdmin(): bool
    {
        return $this->authChecker->isGranted('ROLE_ADMIN');
    }

    public function isDriver(): bool
    {
        return $this->authChecker->isGranted('ROLE_DRIVER');
    }

    public function isEmployee(): bool
    {
        return $this->authChecker->isGranted('ROLE_EMPLOYEE');
    }

    public function isBanned(): bool
    {
        $user = $this->getInternUser();
        return $user instanceof User && $user->isBanned();
    }

    public function isOwnerByEntityRelation(mixed $subject): bool
    {
        $user = $this->getInternUser();
        return $subject->getOwner() === $user;
    }

    public function isOwnerByUuid(string $subjectOwnerUuid): bool
    {
        $user = $this->getInternUser();
        return $user instanceof User && $subjectOwnerUuid === $user->getUUid();
    }

    public function denyUnlessLogged(): void
    {
        if (!$this->isLogged()) {
            throw new AccessDeniedHttpException("User is not authenticated");
        }
    }

    public function denyIfBanned(): void
    {
        if ($this->isBanned()) {
            throw new AccessDeniedHttpException("User is banned");
        }
    }

    public function denyUnlessAdmin(): void
    {
        if (!$this->isAdmin()) {
            throw new AccessDeniedHttpException("Admin users reserved access");
        }
    }

    public function denyUnlessDriver(): void
    {
        if (!$this->isDriver()) {
            throw new AccessDeniedHttpException("Driver users reserved access");
        }
    }

    public function denyUnlessEmployee(): void
    {
        if (!$this->isEmployee() && !$this->isAdmin()) {
            throw new AccessDeniedHttpException("Employee users reserved access");
        }
    }

    public function denyUnlessOwnerByRelation(mixed $subject): void
    {
        if (!$this->isOwnerByEntityRelation($subject)) {
            throw new AccessDeniedHttpException("User not have permission to access this ressource");
        }
    }

    public function denyUnlessOwnerByUuid(string $subjectOwnerUuid): void
    {
        if (!$this->isOwnerByUuid($subjectOwnerUuid)) {
            throw new AccessDeniedHttpException("User not have permission to access this ressource");
        }
    }
}


?>
