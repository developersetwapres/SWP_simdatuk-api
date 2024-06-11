<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 160)->comment('nama diklat');
            $table->string('level', 160)->nullable()->comment('jenjang');
            $table->tinyInteger('period_month')->nullable()->comment('bulan jenjang');
            $table->year('period_year')->nullable()->comment('tahun jenjang');
            $table->date('start_date')->nullable()->comment('tanggal dimulai');
            $table->tinyInteger('duration')->nullable()->comment('in days');
            $table->string('organizer', 160)->nullable()->comment('penyelenggara');
            $table->string('reference_number', 160)->nullable();
            $table->text('link')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1=Struktural, 2=Fungsional, 3=Teknis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
