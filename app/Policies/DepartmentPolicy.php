<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('School Admin') || $user->hasRole('Super Admin');
    }

    public function view(User $user, Department $department): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $department->school_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('School Admin') || $user->hasRole('Super Admin');
    }

    public function update(User $user, Department $department): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $department->school_id;
    }

    public function delete(User $user, Department $department): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && $user->school_id === $department->school_id;
    }
}
