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
        Schema::create('user_recognitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recognition_id');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recognition_id')->references('id')->on('recognitions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recognitions');
    }
};
