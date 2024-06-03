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
        Schema::create('position_history_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('position_history_id');
            $table->string('position', 1024)->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->tinyInteger('echelon')->nullable()->comment('1=Eselon I, 2=Eselon II, 3=Eselon III, 4=Fungsional, 5=Pelaksana, 6=Staf');
            $table->tinyInteger('position_status')->nullable()->comment('1=Promosi, 2=Mutasi, 3=Inpassing, 4=Konversi');
            $table->date('effective_date')->nullable();
            $table->string('decree')->nullable();
            $table->string('decree_document')->nullable();
            $table->unsignedBigInteger('type_of_decree')->nullable();
            $table->string('decree_number')->nullable();
            $table->date('decree_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('termination_decree')->nullable();
            $table->unsignedBigInteger('type_of_termination_decree')->nullable();
            $table->string('termination_decree_number')->nullable();
            $table->date('termination_decree_date')->nullable();
            $table->boolean('status')->nullable()->comment('false=tidak aktif, true=aktif');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('position_history_id')->references('id')->on('position_histories')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('type_of_decree')->references('id')->on('decrees')->onDelete('set null');
            $table->foreign('type_of_termination_decree')->references('id')->on('decrees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_history_users');
    }
};
