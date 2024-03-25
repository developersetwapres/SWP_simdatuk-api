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
        Schema::create('user_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('pegawai_id')->nullable(false);
            $table->string('email', 160)->nullable(false);
            $table->string('username', 160)->nullable(false)->unique('user_registrations_username_unique');
            $table->boolean('is_verified')->nullable(false)->default(false);
            $table->string('verification_key', 255)->nullable(false)->unique('user_registrations_key_unique');
            $table->timestamp('expired_at')->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('pegawai_id')->references('id')->on('pegawai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_registrations');
    }
};
