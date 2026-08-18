<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('whatsapp_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('pin_code')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('experience_years')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('current_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('notice_period')->nullable();
            $table->text('skills')->nullable();
            $table->text('languages')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->boolean('declaration_accepted')->default(true);
            $table->enum('status', [
                'submitted',
                'new',
                'under_review',
                'shortlisted',
                'interview_scheduled',
                'interview_completed',
                'selected',
                'rejected',
                'on_hold',
                'hired'
            ])->default('submitted');
            $table->text('admin_notes')->nullable();
            $table->boolean('is_bookmarked')->default(false);
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'status']);
            $table->index(['vacancy_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
