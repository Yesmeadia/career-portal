<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('vacancy_type', ['campus', 'class'])->default('campus');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('global_class_id')->nullable()->constrained('global_classes')->onDelete('set null');
            $table->foreignId('job_category_id')->constrained('job_categories')->onDelete('cascade');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'temporary'])->default('full_time');
            $table->string('experience_level')->nullable(); // e.g. "0-2 years", "3-5 years"
            $table->string('min_qualification')->nullable();
            $table->decimal('salary_from', 12, 2)->nullable();
            $table->decimal('salary_to', 12, 2)->nullable();
            $table->string('salary_currency')->default('INR');
            $table->enum('gender_preference', ['any', 'male', 'female'])->default('any');
            $table->integer('number_of_vacancies')->default(1);
            $table->string('location')->nullable();
            $table->longText('description');
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->date('deadline')->nullable();
            $table->date('publish_date')->nullable();
            $table->enum('status', ['draft', 'published', 'closed', 'expired', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('seo_url')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'status']);
            $table->index(['status', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
