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
        Schema::create('user_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('target_id');
            $table->tinyInteger('work_behavior_rating')->default(1)->comment('1 = Dibawah ekspektasi, 2 = Sesuai ekspektasi, 3 = Diatas ekspektasi');
            $table->tinyInteger('employee_performance_predicate')->default(1)->comment('1 = Baik, 2 = Sangat Baik');
            $table->tinyInteger('organizational_performance_achievement')->default(1)->comment('1 = Baik, 2 = Sangat Baik');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('targets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_targets');
    }
};
