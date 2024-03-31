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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('email', 160)->nullable()->unique();
            $table->string('username', 160)->nullable()->unique();
            $table->string('password', 255)->nullable();
            $table->string('name', 160);
            $table->string('file_foto_profil', 160)->nullable();
            $table->string('nip', 20)->nullable()->unique();
            $table->string('nrp', 20)->nullable()->unique();
            $table->string('tempat_lahir', 30)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->smallInteger('agama')->default(1)->comment('1=Islam, 2=Kristen, 3=Katolik, 4=Hindu, 5=Buddha, 6=Konghucu');
            $table->boolean('jenis_kelamin')->default(true)->comment('true=Pria, false=Wanita');
            $table->smallInteger('status_perkawinan')->default(1)->comment('1=Belum Menikah, 2=Menikah, 3=Cerai, 4=Janda, 5=Duda');
            $table->unsignedBigInteger('golongan')->nullable(); // Dropdown
            $table->date('tmt_golongan')->nullable();
            $table->unsignedBigInteger('jabatan')->nullable(); // Dropdown
            $table->unsignedBigInteger('eselon')->nullable(); // Dropdown
            $table->date('tmt_eselon')->nullable();
            $table->unsignedBigInteger('instansi_induk')->nullable(); // Dropdown
            $table->unsignedBigInteger('satuan_organisasi')->nullable(); // Dropdown
            $table->unsignedBigInteger('unit_kerja')->nullable(); // Dropdown
            $table->string('no_karpeg', 20)->nullable();
            $table->string('file_kartu_pegawai', 160)->nullable();
            $table->string('no_karis', 20)->nullable();
            $table->string('no_karsu', 20)->nullable();
            $table->string('npwp', 20)->nullable();
            $table->boolean('status_pegawai')->default(true)->comment('true=aktif, false=tidak aktif');
            $table->boolean('komplek')->default(true)->comment('true=dalam, false=luar');
            $table->string('nama_komplek', 160)->nullable();
            $table->text('alamat_tempat_tinggal_saat_ini')->nullable();
            $table->string('no_telepon_rumah', 20)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat_kantor', 160)->nullable();
            $table->string('no_telepon_kantor', 20)->nullable();
            $table->tinyInteger('type')->nullable()->default(1)->comment('1=ASN, 2=NON ASN, 3=OUTSOURCING');
            $table->boolean('status')->default(false)->comment('true=active, false=deactivate');
            $table->text('verification_code')->nullable();
            $table->date('expire_at')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
