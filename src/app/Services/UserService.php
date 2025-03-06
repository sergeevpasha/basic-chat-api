<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Create a new user if one doesn't exist with the given login, or retrieve an existing one.
     *
     * @param string $login The user's login
     * @return array The user instance and a boolean indicating if it was newly created
     */
    public function createOrRetrieveUser(string $login): array
    {
        $user = User::firstOrCreate(
            ['login' => $login],
        );

        $isNewUser = $user->wasRecentlyCreated;

        return [
            'user' => $user,
            'isNewUser' => $isNewUser,
        ];
    }
}
