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
        Schema::create('echelons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 160)->unique();
            $table->tinyInteger('sequence_number')->nullable();
            $table->integer('retirement_age')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('echelons');
    }
};
