<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('postal_code')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('timezone')->default('Asia/Kolkata');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
