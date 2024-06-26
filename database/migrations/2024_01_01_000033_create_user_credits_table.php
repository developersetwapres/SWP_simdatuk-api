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
        Schema::create('user_credits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('position', 160)->nullable();
            $table->tinyInteger('period')->comment('1=Triwulan 1, 2=Triwulan 2, 3=Triwulan 3, 4=Triwulan 4, 5=Tahunan');
            $table->year('year');
            $table->tinyInteger('start_month')->nullable();
            $table->tinyInteger('end_month')->nullable();
            $table->float('score', 5, 2)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_credits');
    }
};
