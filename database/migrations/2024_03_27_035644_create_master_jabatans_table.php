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
        Schema::create('master_jabatan', function (Blueprint $table) {
            $table->id();

            $table->string('nama', 160)->nullable(false)->unique('master_jabatan_nama_unique');
            $table->bigInteger('jumlah_diperlukan')->nullable(false)->default(1);
            
            $table->unsignedBigInteger('eselon_id')->nullable(true);
            $table->unsignedBigInteger('deputi_id')->nullable(true);
            $table->unsignedBigInteger('biro_id')->nullable(true);
            $table->unsignedBigInteger('bagian_id')->nullable(true);
            $table->unsignedBigInteger('subbagian_id')->nullable(true);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('eselon_id')->references('id')->on('eselon');
            $table->foreign('deputi_id')->references('id')->on('deputi');
            $table->foreign('biro_id')->references('id')->on('biro');
            $table->foreign('bagian_id')->references('id')->on('bagian');
            $table->foreign('subbagian_id')->references('id')->on('subbagian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jabatan');
    }
};
