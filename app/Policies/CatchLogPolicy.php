<?php

namespace App\Policies;

use App\Models\CatchLog;
use App\Models\User;

class CatchLogPolicy
{
    public function update(User $user, CatchLog $catch): bool
    {
        return $user->id === $catch->user_id;
    }

    public function delete(User $user, CatchLog $catch): bool
    {
        return $user->id === $catch->user_id;
    }
}
