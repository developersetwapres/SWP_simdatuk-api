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
        Schema::create('user_educations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('level')->default(1)->comment('1=TK / Sederajat, 2=SD / Sederajat, 3=SLTP / Sederajat, 4=SLTA / Sederajat, 5=Diploma I / II, 6=Akademi / Diploma III / Sarjana Muda, 7=Diploma IV / Strata I, 8=Strata II, 9=Strata III');
            $table->string('name', 160);
            $table->string('faculty', 160)->nullable();
            $table->string('major', 160)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Lulus, 2=DO, 3=Aktif, 4=Non-Aktif, 5=Mengundurkan diri');
            $table->year('year_of_graduation');
            $table->text('description')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_educations');
    }
};
