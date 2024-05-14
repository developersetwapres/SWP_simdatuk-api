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
        Schema::create('user_disciplinaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('disciplinary_id');
            $table->string('grade');
            $table->string('position');
            $table->unsignedBigInteger('disciplinary_type_id');
            $table->string('decree_number', 160)->nullable();
            $table->date('date_of_decree')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('authorizing_officer')->nullable();
            $table->string('name_of_authorizing_officer', 160)->nullable();
            $table->string('description', 160)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('disciplinary_id')->references('id')->on('disciplinaries')->onDelete('cascade');
            $table->foreign('disciplinary_type_id')->references('id')->on('disciplinary_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_disciplinaries');
    }
};
