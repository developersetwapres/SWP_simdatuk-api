<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Biro extends Model
{
    protected $table = "biro";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'nama',
        'deputi_id',
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
