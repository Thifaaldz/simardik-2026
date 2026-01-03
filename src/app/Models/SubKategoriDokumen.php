<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategoriDokumen extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'sub_kategori_dokumens';

    protected $fillable = [
        'kategori_dokumen_id',
        'nama_sub_kategori',
        'deskripsi',
        'butuh_student',
        'butuh_pkl',
        'butuh_pegawai',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'sub_kategori_dokumen_id');
    }
}
