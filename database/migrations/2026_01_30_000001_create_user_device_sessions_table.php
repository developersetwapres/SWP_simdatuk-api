<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_device_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('device_identifier'); // Unique device identifier
            $table->string('device_name')->nullable(); // Device name (optional)
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('sanctum_token_id')->nullable(); // Link to Sanctum token
            $table->timestamp('last_activity_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'device_identifier']);
            $table->index('sanctum_token_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_device_sessions');
    }
};