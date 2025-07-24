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
        Schema::create('grade_history_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grade_history_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('decree_name', 512)->nullable();
            $table->string('decree_document')->nullable();
            $table->unsignedBigInteger('type_of_decree')->nullable();
            $table->string('decree_number', 160)->nullable();
            $table->date('decree_date')->nullable();
            $table->string('description', 160)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=tidak aktif, 1=aktif');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('grade_history_id')->references('id')->on('grade_histories')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('type_of_decree')->references('id')->on('decrees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_history_users');
    }
};
