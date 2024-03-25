<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('role_id')->nullable(true);
            $table->string('username', 160)->nullable(true)->unique('pegawai_username_unique');
            $table->string('password', 255)->nullable(true);
            $table->boolean('role_status')->default(false);

            $table->string('file_foto_profile', 160);
            $table->string('nama', 160)->nullable(false);
            $table->string('nip', 160)->nullable(false)->unique('pegawai_nip_unique');
            $table->string('nrp', 160)->nullable(false)->unique('pegawai_nrp_unique');
            $table->string('tempat_lahir', 160);
            $table->date('tanggal_lahir');
            $table->string('agama', 160);
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('status_perkawinan', 160);
            $table->string('golongan', 160);
            $table->string('tmt_golongan', 160);
            $table->string('jabatan', 160);
            $table->string('eselon', 160);
            $table->string('tmt_eselon', 160);
            $table->string('instansi_induk', 160);
            $table->string('satuan_organisasi', 160);
            $table->string('unit_kerja', 160);
            $table->string('no_karpeg', 160);
            $table->string('no_karis', 160);
            $table->string('no_karsu', 160);
            $table->string('npwp', 160);
            $table->string('status_pegawai', 160);
            $table->string('komplek', 160);
            $table->string('alamat_tempat_tinggal_saat_ini', 160);
            $table->string('no_telepon_rumah', 160);
            $table->string('no_hp', 160);
            $table->string('alamat_kantor', 160);
            $table->string('no_telepon_kantor', 160);
            $table->string('email', 160);
            $table->string('type', 160);
            $table->boolean('status')->default(true);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
