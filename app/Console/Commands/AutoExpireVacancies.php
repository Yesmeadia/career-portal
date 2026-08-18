<?php

namespace App\Console\Commands;

use App\Models\Vacancy;
use Illuminate\Console\Command;

class AutoExpireVacancies extends Command
{
    protected $signature = 'vacancies:expire';
    protected $description = 'Automatically update status of vacancies past their deadline to expired';

    public function handle(): int
    {
        $expiredCount = Vacancy::withoutGlobalScopes()
            ->where('status', 'published')
            ->whereNotNull('deadline')
            ->where('deadline', '<', now()->toDateString())
            ->update(['status' => 'expired']);

        $this->info("Successfully expired {$expiredCount} vacancies.");

        return Command::SUCCESS;
    }
}
