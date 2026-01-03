<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'sekolahs';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'alamat',
        'akreditasi',
    ];

    public function unitKerja()
    {
        return $this->hasMany(UnitKerja::class);
    }
}
