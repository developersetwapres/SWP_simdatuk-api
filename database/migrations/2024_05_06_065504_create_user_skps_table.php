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
        Schema::create('user_skps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('skp_id');
            $table->enum('rating_work_behavior', ['Di bawah ekspektasi', 'Sesuai ekspektasi', 'Diatas ekspektasi']);
            $table->enum('employee_performance_predicate', ['Baik', 'Sangat Baik']);
            $table->enum('organization_performance_achievement', ['Baik', 'Sangat Baik']);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('skp_id')->references('id')->on('skps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skps');
    }
};
