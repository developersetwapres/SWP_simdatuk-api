<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    protected $table = "pegawai";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'role_id',
        'username',
        'password',
        'role_status',
        'file_foto_profile',
        'nama',
        'nip',
        'nrp',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'status_perkawinan',
        'golongan',
        'tmt_golongan',
        'jabatan',
        'eselon',
        'tmt_eselon',
        'instansi_induk',
        'satuan_organisasi',
        'unit_kerja',
        'no_karpeg',
        'no_karis',
        'no_karsu',
        'npwp',
        'status_pegawai',
        'komplek',
        'alamat_tempat_tinggal_saat_ini',
        'no_telepon_rumah',
        'no_hp',
        'alamat_kantor',
        'no_telepon_kantor',
        'email',
        'type',
        'status'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
