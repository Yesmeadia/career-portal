<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Interview;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
    public function run(): void
    {
        $applications = Application::all();

        foreach ($applications as $index => $app) {
            if (Interview::where('application_id', $app->id)->exists()) {
                continue;
            }

            Interview::create([
                'application_id' => $app->id,
                'school_id' => $app->school_id,
                'scheduled_date' => now()->addDays(($index + 1) * 2)->toDateString(),
                'scheduled_time' => '10:30:00',
                'location_type' => $index % 2 === 0 ? 'in_person' : 'online',
                'location_address_or_link' => $index % 2 === 0 ? 'Main Campus - Administrative Boardroom (Block B)' : 'https://meet.google.com/sch-recruitment-desk',
                'panel_members' => null,
                'status' => 'scheduled',
                'remarks' => null,
                'score' => null,
            ]);

            // Ensure application status reflects scheduled interview
            if (!in_array($app->status, ['hired', 'selected', 'rejected'])) {
                $app->update(['status' => 'interview_scheduled']);
            }
        }
    }
}
