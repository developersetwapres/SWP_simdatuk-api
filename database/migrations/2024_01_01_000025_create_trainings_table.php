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
        Schema::create('trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 512)->nullable();
            $table->string('level', 160)->nullable();
            $table->tinyInteger('period_month')->nullable();
            $table->year('period_year')->nullable();
            $table->date('start_date')->nullable();
            $table->smallInteger('duration')->nullable()->comment('in days');
            $table->string('organizer', 512)->nullable();
            $table->string('reference_number', 160)->nullable();
            $table->text('link')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1=Struktural, 2=Fungsional, 3=Teknis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
