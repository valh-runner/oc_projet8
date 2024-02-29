<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    // these strings are just invented: you can use anything
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Task object, thanks to `supports()`
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
        if ($this->canDelete($task, $user)) { // if they can edit, they can delete
            return true;
        }
        return false;
    }

    private function canDelete(Task $task, User $user): bool
    {
        // if user if the owner
        if ($user === $task->getOwner()) {
            return true;
        }
        // if task owned by anonym user and authenticated user is admin
        elseif ($task->getOwner()->getUsername() == 'anonym' && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return false;
    }
}
