<?php


namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

/**
 *
 */
class SubjectPolicy
{
    /**
     * @param User|null $user
     * @param Subject $subject
     * @return bool
     */
    public function view(?User $user, Subject $subject)
    {
        return true;
    }

    /**
     * @param User|null $user
     * @param Subject $subject
     * @return bool
     */
    public function edit(?User $user, Subject $subject)
    {
        return true;
    }
}
