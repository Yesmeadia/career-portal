<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('School Admin');
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $application->school_id;
    }

    public function update(User $user, Application $application): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $application->school_id;
    }

    public function delete(User $user, Application $application): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $application->school_id;
    }
}
