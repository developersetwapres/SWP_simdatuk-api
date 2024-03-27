<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deputi extends Model
{
    use HasFactory;

    protected $table = "deputi";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'nama'
    ];

    public function biro(): HasMany
    {
        return $this->hasMany(Biro::class, 'deputi_id');
    }

    public function bagian(): HasMany
    {
        return $this->hasMany(Bagian::class, 'deputi_id');
    }

    public function subbagian(): HasMany
    {
        return $this->hasMany(Subbagian::class, 'deputi_id');
    }

    public function masterJabatan(): HasMany
    {
        return $this->hasMany(MasterJabatan::class, 'eselon_id');
    }
}
