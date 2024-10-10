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
        Schema::create('user_talents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('event_date')->nullable();
            $table->tinyInteger('point')->nullable()->comment('1=Kotak 1, 2=Kotak 2, 3=Kotak 3, 4=Kotak 4, 5=Kotak 5, 6=Kotak 6, 7=Kotak 7, 8=Kotak 8, 9=Kotak 9');
            $table->string('organizer', 512)->nullable();
            $table->string('talent_document')->nullable();
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
        Schema::dropIfExists('user_talents');
    }
};
