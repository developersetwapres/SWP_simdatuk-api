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
        Schema::create('recognition_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('period_month')->nullable();
            $table->year('period_year')->nullable();
            $table->unsignedBigInteger('recognition_id')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('type_of_decree')->nullable();
            $table->date('decree_date')->nullable();
            $table->string('decree_number', 160)->nullable();
            $table->year('decree_year')->nullable();
            $table->string('awarding_institution', 160)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('recognition_id')->references('id')->on('recognitions')->onDelete('cascade');
            $table->foreign('type_of_decree')->references('id')->on('decrees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recognition_histories');
    }
};
