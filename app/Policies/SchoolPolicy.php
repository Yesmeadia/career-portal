<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;

class SchoolPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function view(User $user, School $school): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $school->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function update(User $user, School $school): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $school->id;
    }

    public function delete(User $user, School $school): bool
    {
        return $user->hasRole('Super Admin');
    }
}
