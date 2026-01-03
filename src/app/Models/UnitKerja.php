<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'unit_kerjas';

    protected $fillable = [
        'nama_unit',
        'jenis_unit',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
