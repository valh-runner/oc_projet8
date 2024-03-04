<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    // These strings are just invented: you can use anything
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // If the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // Only vote on `Post` objects
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // The user must be logged in; if not, deny access
            return false;
        }

        // You know $subject is a Task object, thanks to `supports()`
        /** @var Task $task */
        $task = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($task, $user),
            self::DELETE => $this->canDelete($task, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit(Task $task, User $user): bool
    {
        if ($this->canDelete($task, $user)) { // If they can edit, they can delete
            return true;
        }
        return false;
    }

    private function canDelete(Task $task, User $user): bool
    {
        // If user if the owner
        if ($user === $task->getOwner()) {
            return true;
        }
        // If task owned by anonym user and authenticated user is admin
        elseif ($task->getOwner()->getUsername() == 'anonym' && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return false;
    }
}
