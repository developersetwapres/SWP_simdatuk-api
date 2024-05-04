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
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->string('name', 160);
            $table->string('level', 160)->nullable();
            $table->date('start_date');
            $table->tinyInteger('duration')->nullable()->comment('in days');
            $table->string('organizer', 160)->nullable();
            $table->string('reference_number', 160);
            $table->text('link')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1=Struktural, 2=Fungsional, 3=Teknis');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
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
