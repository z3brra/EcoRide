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

        $priority = ['owner', 'participants', 'not_participant', 'capacity', 'duplicate', 'status'];
        $selected = null;

        foreach ($priority as $wanted) {
            foreach ($blockers as $blocker) {
                if ($blocker->getCode() === $wanted) {
                    $selected = $blocker;
                    break 2;
                }
            }
        }
        $selected ??= iterator_to_array($blockers)[0] ?? null;

        $code = $selected->getCode();
        $message = $selected->getMessage() ?? $code ?? 'Transition blocked';

        $status = match ($code) {
            'owner' => 403,
            'participants' => 400,
            'not_participant' => 400,
            'credits' => 400,
            'capacity' => 409,
            'duplicate' => 409,
            'status' => 409,
            default => 409,
        };

        throw new DriveTransitionException([$message], $status);
    }
}

?>