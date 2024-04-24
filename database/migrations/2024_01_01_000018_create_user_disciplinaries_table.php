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
        Schema::create('user_disciplinaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->string('grade_id');
            $table->string('position', 160);
            $table->string('penalty', 160);
            $table->string('decree_number', 160);
            $table->date('date_of_decree');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status');
            $table->string('description', 160);
            $table->unsignedBigInteger('authorizing_officer');
            $table->string('name_of_authorizing_officer', 160);
            $table->tinyInteger('level');
            $table->tinyInteger('type');
            $table->tinyInteger('validity_period');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_disciplinaries');
    }
};
