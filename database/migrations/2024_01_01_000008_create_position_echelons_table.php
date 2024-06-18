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
        Schema::create('position_echelons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('echelon_id')->nullable();
            $table->tinyInteger('available')->default(0);
            $table->tinyInteger('filled')->default(0);
            $table->tinyInteger('children')->default(0);
            $table->tinyInteger('vertical_order')->default(0);
            $table->tinyInteger('horizontal_order')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('echelon_id')->references('id')->on('echelons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_echelons');
    }
};
