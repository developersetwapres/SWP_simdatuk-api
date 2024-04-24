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
        Schema::create('user_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->unsignedBigInteger('grade_id');
            $table->date('effective_date');
            $table->string('decree_name', 160);
            $table->string('decree_document');
            $table->unsignedBigInteger('type_of_decree');
            $table->string('decree_number', 160);
            $table->date('decree_date');
            $table->string('description', 160);
            $table->tinyInteger('status');
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
        Schema::dropIfExists('user_grades');
    }
};
