<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dudi extends Model
{
    protected $fillable = [
        'nama_dudi',
        'alamat',
        'bidang',
    ];

    public function pkl()
    {
        return $this->hasMany(Pkl::class);
    }
}
