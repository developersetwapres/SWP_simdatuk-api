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
            $table->tinyInteger('level')->nullable()->comment('1=SD/Sederajat, 2=SLTP/Sederajat, 3=SLTA/Sederajat, 4=Diploma I/II, 5=Akademik/D3/S.Muda, 6=Diploma IV/Strata I, 7=Strata II, 8=Strata III');
            $table->string('name', 160)->nullable();
            $table->tinyInteger('study_area')->nullable()->comment('1=Dalam Negeri, 2=Luar Negeri');
            $table->string('accreditation', 30)->nullable()->comment('Akreditasi A, B, C, atau BAN-PT Unggul, Baik Sekali, Baik');
            $table->string('faculty', 160)->nullable();
            $table->string('major', 160)->nullable();
            $table->tinyInteger('status')->nullable()->comment('1=Lulus, 2=DO, 3=Aktif, 4=Non-Aktif, 5=Mengundurkan diri');
            $table->year('year_of_graduation')->nullable();
            $table->text('description')->nullable();
            $table->text('degree_document')->nullable()->comment('Dokumen Ijazah / SKL / Sejenisnya');
            $table->string('study_assignment_letter', 512)->nullable()->comment('Surat Keterangan Tugas Belajar');
            $table->string('academic_title_letter', 512)->nullable()->comment('Surat Keputusan Pencantuman Gelar');
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
        Schema::dropIfExists('user_educations');
    }
};
