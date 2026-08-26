<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop existing foreign key on vacancy_id to make it nullable
            $table->dropForeign(['vacancy_id']);
            $table->dropForeign(['school_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('vacancy_id')->nullable()->change();
            $table->unsignedBigInteger('school_id')->nullable()->change();

            $table->foreign('vacancy_id')->references('id')->on('vacancies')->nullOnDelete();
            $table->foreign('school_id')->references('id')->on('schools')->nullOnDelete();

            $table->string('application_type', 30)->default('vacancy')->after('reference_no');
            $table->string('preferred_position', 255)->nullable()->after('school_id');
            $table->string('preferred_department', 255)->nullable()->after('preferred_position');

            $table->index(['application_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['application_type', 'status']);
            $table->dropColumn(['application_type', 'preferred_position', 'preferred_department']);

            $table->dropForeign(['vacancy_id']);
            $table->dropForeign(['school_id']);

            $table->unsignedBigInteger('vacancy_id')->nullable(false)->change();
            $table->unsignedBigInteger('school_id')->nullable(false)->change();

            $table->foreign('vacancy_id')->references('id')->on('vacancies')->cascadeOnDelete();
            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
        });
    }
};
