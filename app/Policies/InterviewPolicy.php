<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('School Admin');
    }

    public function view(User $user, Interview $interview): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $interview->school_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('School Admin') || $user->hasRole('Super Admin');
    }

    public function update(User $user, Interview $interview): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $interview->school_id;
    }
}
