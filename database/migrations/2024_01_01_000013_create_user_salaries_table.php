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
        Schema::create('user_salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->unsignedBigInteger('grade_id');
            $table->date('effective_date');
            $table->string('decree_number', 160);
            $table->string('length_of_service_month', 2);
            $table->year('length_of_service_year');
            $table->integer('previous_basic_salary');
            $table->integer('new_basic_salary');
            $table->string('description', 160);
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
        Schema::dropIfExists('user_salaries');
    }
};
