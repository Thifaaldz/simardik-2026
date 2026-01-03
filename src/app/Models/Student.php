<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'kelas_id',
        'jurusan_id',
        'tahun_ajaran_id',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function pkl()
    {
        return $this->hasMany(Pkl::class);
    }
}
