<?php


namespace App\Policies;


use App\Models\User;

class AssetPolicy
{
    /**
     * @param User|null $user
     * @return bool
     */
    public function upload(?User $user)
    {
        if (!$user) {
            return false;
        }

        return true;
    }
}
