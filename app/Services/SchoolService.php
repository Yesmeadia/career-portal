<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use App\Models\ActivityLog;
use App\Repositories\Contracts\SchoolRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SchoolService
{
    public function __construct(
        protected SchoolRepositoryInterface $schoolRepository
    ) {}

    public function createSchoolWithAdmin(array $schoolData, array $adminData): School
    {
        return DB::transaction(function () use ($schoolData, $adminData) {
            $school = $this->schoolRepository->create($schoolData);

            $admin = User::create([
                'name' => $adminData['name'],
                'email' => $adminData['email'],
                'password' => Hash::make($adminData['password']),
                'school_id' => $school->id,
                'phone' => $adminData['phone'] ?? null,
                'status' => 'active',
            ]);

            $admin->assignRole('School Admin');

            ActivityLog::record("Created school {$school->name} and assigned admin {$admin->email}", $school, 'schools');

            return $school;
        });
    }

    public function updateSchool(School $school, array $data): bool
    {
        $schoolData = array_diff_key($data, array_flip(['admin_name', 'admin_email', 'admin_password']));
        $updated = $this->schoolRepository->update($school, $schoolData);

        // Update or create assigned School Admin user
        if (!empty($data['admin_email']) || !empty($data['admin_name'])) {
            $adminUser = $school->users()->first();
            if ($adminUser) {
                if (!empty($data['admin_name'])) $adminUser->name = $data['admin_name'];
                if (!empty($data['admin_email'])) $adminUser->email = $data['admin_email'];
                if (!empty($data['admin_password'])) $adminUser->password = Hash::make($data['admin_password']);
                $adminUser->save();
            } elseif (!empty($data['admin_email']) && !empty($data['admin_name'])) {
                $newAdmin = User::create([
                    'name'      => $data['admin_name'],
                    'email'     => $data['admin_email'],
                    'password'  => Hash::make($data['admin_password'] ?? 'Admin@1234'),
                    'school_id' => $school->id,
                    'status'    => 'active',
                ]);
                $newAdmin->assignRole('School Admin');
            }
        }

        if ($updated) {
            ActivityLog::record("Updated school {$school->name}", $school, 'schools');
        }
        return $updated;
    }

    public function toggleSchoolStatus(School $school): bool
    {
        $newStatus = $school->status === 'active' ? 'inactive' : 'active';
        $updated = $this->schoolRepository->update($school, ['status' => $newStatus]);
        if ($updated) {
            ActivityLog::record("Changed status of school {$school->name} to {$newStatus}", $school, 'schools');
        }
        return $updated;
    }
}
