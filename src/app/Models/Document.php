<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'kode_dokumen',
        'nama_dokumen',
        'kategori_dokumen_id',
        'sub_kategori_dokumen_id',
        'unit_kerja_id',
        'student_id',
        'pegawai_id',
        'pkl_id',
        'tahun',
        'tanggal_dokumen',
        'status_dokumen',
        'tingkat_kerahasiaan',
        'file_path',
        'disk',
        'file_hash',
        'mime_type',
        'file_size',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
    }

    public function subKategori()
    {
        return $this->belongsTo(SubKategoriDokumen::class, 'sub_kategori_dokumen_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pkl()
    {
        return $this->belongsTo(Pkl::class);
    }

    public function metadata()
    {
        return $this->hasMany(DocumentMetadata::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
