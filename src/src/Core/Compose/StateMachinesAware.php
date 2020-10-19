<?php

declare(strict_types=1);

namespace App\Core\Compose;


use App\Entity\Compose\StatusInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Workflow\Registry;

trait StateMachinesAware
{
    /**
     * @var Registry
     */
    private $stateMachines;

    /**
     * @required
     */
    public function setStateMachines(Registry $stateMachines): self
    {
        $this->stateMachines = $stateMachines;

        return $this;
    }

    protected function applyTransition(string $transition, StatusInterface $subject, array $context = []): void
    {
        $machine = $this->stateMachines->get($subject);
        if (false === $machine->can($subject, $transition)) {
            dd($subject->status);
            throw new AccessDeniedException(
                "Not authorized to apply [{$transition}] from {$subject->getPlace()}."
            );
        }
        $machine->apply($subject, $transition, $context);
    }
}
