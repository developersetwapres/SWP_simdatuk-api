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
        Schema::create('user_families', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('card_number', 160)->nullable();
            $table->string('name', 160)->nullable();
            $table->string('id_number', 20)->nullable();
            $table->boolean('gender')->nullable()->comment('true=Pria, false=Wanita');
            $table->tinyInteger('religion')->nullable()->comment('1=Islam, 2=Kristen, 3=Katolik, 4=Hindu, 5=Buddha, 6=Konghucu');
            $table->string('place_of_birth', 160)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('name_of_father', 160)->nullable();
            $table->string('name_of_mother', 160)->nullable();
            $table->tinyInteger('relationship_status')->nullable()->comment('1=Kepala Keluarga, 2=Suami, 3=Istri, 4=Anak, 5=Menantu, 6=Cucu, 7=Orang Tua, 8=Mertua, 9=Famili Lainnya, 10=Pembantu, 11=Lainnya');
            $table->tinyInteger('education')->nullable()->comment('1=Tidak/Belum Sekolah, 2=Belum Tamat SD/Sederajat, 3=Tamat SD/Sederajat, 4=SLTP/Sederajat, 5=SLTA/Sederajat, 6=Diploma I/II, 7=Akademi/Diploma III/Sarjana Muda, 8=Diploma IV/Strata I, 9=Strata II, 10=Strata III');
            $table->string('occupation', 160)->nullable();
            $table->string('occupation_description', 160)->nullable();
            $table->tinyInteger('marital_status')->nullable()->comment('1=Belum Menikah, 2=Menikah, 3=Cerai Hidup, 4=Cerai Mati');
            $table->string('mobile_phone', 16)->nullable();
            $table->tinyInteger('sequence_number')->nullable();
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
        Schema::dropIfExists('user_families');
    }
};
