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
        Schema::create('recognitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->string('name', 160)->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('type_of_decree')->nullable();
            $table->date('decree_date')->nullable();
            $table->string('decree_number', 160)->nullable();
            $table->year('decree_year')->nullable();
            $table->string('awarding_institution', 160)->nullable();
            $table->date('date_of_receipt')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('type_of_decree')->references('id')->on('decrees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recognitions');
    }
};
