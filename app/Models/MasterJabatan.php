<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterJabatan extends Model
{
    use HasFactory;

    protected $table = "master_jabatan";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'nama',
        'jumlah_diperlukan',
        'eselon_id',
        'deputi_id',
        'biro_id',
        'bagian_id',
        'subbagian_id'
    ];

    public function eselon(): BelongsTo
    {
        return $this->belongsTo(Eselon::class, 'eselon_id');
    }
    
    public function deputi(): BelongsTo
    {
        return $this->belongsTo(Deputi::class, 'deputi_id');
    }
    
    public function biro(): BelongsTo
    {
        return $this->belongsTo(Biro::class, 'biro_id');
    }
    
    public function bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian_id');
    }
    
    public function subbagian(): BelongsTo
    {
        return $this->belongsTo(Subbagian::class, 'subbagian_id');
    }
}
