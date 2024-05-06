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
        Schema::create('skps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 160);
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->enum('period_review', ['Q1', 'Q2', 'Q3', 'Q4']);
            $table->year('year');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skps');
    }
};
