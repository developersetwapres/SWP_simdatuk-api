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
        Schema::create('performance_history_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('performance_history_id');
            $table->unsignedBigInteger('user_id');
            $table->float('work_performance_score', 5, 2)->nullable();
            $table->tinyInteger('description')->nullable()->comment('1=Kurang (<50), 2=Sedang (51-60), 3=Cukup (61-75), 4=Baik (76-90), 5=Sangat Baik (91-100)');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('performance_history_id')->references('id')->on('performance_histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_history_users');
    }
};
