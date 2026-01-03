<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'pegawais';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'status_kepegawaian',
        'unit_kerja_id',
    ];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function pklPembimbing()
    {
        return $this->hasMany(Pkl::class, 'pembimbing_id');
    }
}
