<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eselon extends Model
{
    use HasFactory;

    protected $table = "eselon";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;

    protected $fillable = [
        'nama'
    ];

    public function masterJabatan(): HasMany
    {
        return $this->hasMany(MasterJabatan::class, 'eselon_id');
    }
}
