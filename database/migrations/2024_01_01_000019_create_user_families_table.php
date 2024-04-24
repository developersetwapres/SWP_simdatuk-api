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
            $table->string('card_number', 160);
            $table->string('name', 160);
            $table->string('id_number', 17);
            $table->boolean('gender')->default(true)->comment('true=Pria, false=Wanita');
            $table->tinyInteger('religion')->default(1)->comment('1=Islam, 2=Kristen, 3=Katolik, 4=Hindu, 5=Buddha, 6=Konghucu');
            $table->string('place_of_birth', 160);
            $table->date('date_of_birth');
            $table->string('name_of_father', 160);
            $table->string('name_of_mother', 160);
            $table->tinyInteger('relationship_status')->default(1)->comment('1=Kepala Keluarga, 2=Suami, 3=Istri, 4=Anak, 5=Menantu, 6=Cucu, 7=Orang Tua, 8=Mertua, 9=Famili Lainnya, 10=Pembantu, 11=Lainnya');
            $table->tinyInteger('level')->default(1)->comment('1=TK / Sederajat, 2=SD / Sederajat, 3=SLTP / Sederajat, 4=SLTA / Sederajat, 5=Diploma I / II, 6=Akademi / Diploma III / Sarjana Muda, 7=Diploma IV / Strata I, 8=Strata II, 9=Strata III');
            $table->string('occupation', 160);
            $table->string('occupation_description', 160);
            $table->tinyInteger('marital_status')->default(1)->comment('1=Belum Menikah, 2=Menikah, 3=Cerai, 4=Janda, 5=Duda');
            $table->string('mobile_phone', 16);
            $table->tinyInteger('order');
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
        Schema::dropIfExists('user_families');
    }
};
