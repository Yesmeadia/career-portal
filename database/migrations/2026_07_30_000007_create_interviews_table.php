<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->enum('location_type', ['in_person', 'online'])->default('in_person');
            $table->text('location_address_or_link')->nullable();
            $table->text('panel_members')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->text('feedback')->nullable();
            $table->integer('score')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
