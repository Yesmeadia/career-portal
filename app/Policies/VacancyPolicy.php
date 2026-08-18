<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;

class VacancyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('School Admin');
    }

    public function view(User $user, Vacancy $vacancy): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && (int)$user->school_id === (int)$vacancy->school_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('School Admin') || $user->hasRole('Super Admin');
    }

    public function update(User $user, Vacancy $vacancy): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && (int)$user->school_id === (int)$vacancy->school_id;
    }

    public function delete(User $user, Vacancy $vacancy): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return $user->hasRole('School Admin') && (int)$user->school_id === (int)$vacancy->school_id;
    }
}
