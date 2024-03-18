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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('pegawai_id')->nullable(false);
            $table->string('email', 160)->nullable(false);
            $table->string('username', 160)->nullable(false)->unique('users_username_unique');
            $table->string('password', 255)->nullable(false);
            $table->boolean('status')->nullable(false)->default(true);
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
        Schema::dropIfExists('users');
    }
};
