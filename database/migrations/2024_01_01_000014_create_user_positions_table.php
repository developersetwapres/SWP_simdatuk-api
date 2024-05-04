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
        Schema::create('user_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('period_month');
            $table->year('period_year');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('group_id');
            $table->date('effective_date');
            $table->string('decree', 160)->nullable();
            $table->string('decree_document')->nullable();
            $table->unsignedBigInteger('type_of_decree');
            $table->string('decree_number', 160)->nullable();
            $table->date('decree_date')->nullable();
            $table->string('echelon_description', 160)->nullable();
            $table->string('description', 160)->nullable();
            $table->date('termination_date');
            $table->string('termination_decree', 160)->nullable();
            $table->unsignedBigInteger('type_of_termination_decree');
            $table->string('termination_decree_number', 160)->nullable();
            $table->date('termination_decree_date')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('user_positions');
    }
};
