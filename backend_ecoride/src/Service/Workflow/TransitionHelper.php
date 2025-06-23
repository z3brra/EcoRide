<?php

namespace App\Service\Workflow;

use Symfony\Component\Workflow\WorkflowInterface;

use App\Exception\DriveTransitionException;

class TransitionHelper
{
    public function guardAndApply(
        WorkflowInterface $workflow,
        object $subject,
        string $transition
    ): void {
        $blockers = $workflow->buildTransitionBlockerList($subject, $transition);

        if ($blockers->isEmpty()) {
            $workflow->apply($subject, $transition);
            return;
        }

        $selected = null;
        foreach ($blockers as $blocker) {
            $code = $blocker->getCode();

            if (in_array($code, ['owner', 'participants'], true)) {
                $selected = $blocker;
                break;
            }
            $selected ??= $blocker;
        }

        $code = $selected->getCode();
        $message = $selected->getMessage() ?? $code ?? 'Transition blocked';

        $status = match ($code) {
            'owner' => 403,
            'participants' => 400,
            default => 409,
        };

        throw new DriveTransitionException([$message], $status);
    }
}

?>