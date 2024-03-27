<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subbagian extends Model
{
    use HasFactory;

    protected $table = "subbagian";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'nama',
        'deputi_id'
    ];

    public function deputi(): BelongsTo
    {
        return $this->belongsTo(Deputi::class, 'deputi_id');
    }

    public function masterJabatan(): HasMany
    {
        return $this->hasMany(MasterJabatan::class, 'eselon_id');
    }
}
