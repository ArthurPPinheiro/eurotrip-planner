<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    public function view(User $user, Trip $trip): bool {
        return $trip->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Trip $trip): bool {
        return $trip->members()->where('user_id', $user->id)->wherePivotIn('role', ['owner'])->exists();
    }

    public function delete(User $user, Trip $trip): bool {
        return $trip->created_by === $user->id;
    }
}
