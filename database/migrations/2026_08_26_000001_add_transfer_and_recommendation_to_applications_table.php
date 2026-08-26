<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('transferred_from_school_id')
                ->nullable()
                ->after('school_id')
                ->constrained('schools')
                ->onDelete('set null');
            $table->text('recommendation_notes')->nullable()->after('admin_notes');
            $table->timestamp('transferred_at')->nullable()->after('applied_at');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['transferred_from_school_id']);
            $table->dropColumn(['transferred_from_school_id', 'recommendation_notes', 'transferred_at']);
        });
    }
};
