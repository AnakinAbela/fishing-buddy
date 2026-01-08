<?php

namespace App\Policies;

use App\Models\FishingSpot;
use App\Models\User;

class FishingSpotPolicy
{
    public function update(User $user, FishingSpot $spot): bool
    {
        return $user->id === $spot->user_id;
    }

    public function delete(User $user, FishingSpot $spot): bool
    {
        return $user->id === $spot->user_id;
    }
}
