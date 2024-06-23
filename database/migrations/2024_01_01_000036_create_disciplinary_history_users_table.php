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
        Schema::create('disciplinary_history_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('disciplinary_history_id');
            $table->unsignedBigInteger('user_id');
            $table->string('grade')->nullable();
            $table->string('position')->nullable();
            $table->unsignedBigInteger('disciplinary_id')->nullable();
            $table->string('decree_number', 160)->nullable();
            $table->date('date_of_decree')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('authorizing_officer')->nullable();
            $table->string('name_of_authorizing_officer', 160)->nullable();
            $table->string('description', 160)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('disciplinary_history_id')->references('id')->on('disciplinary_histories')->onDelete('cascade');
            $table->foreign('disciplinary_id')->references('id')->on('disciplinaries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinary_history_users');
    }
};
