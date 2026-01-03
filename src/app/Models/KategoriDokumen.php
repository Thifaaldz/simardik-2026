<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'kategori_dokumens';

    protected $fillable = ['nama_kategori'];

    public function subKategori()
    {
        return $this->hasMany(SubKategoriDokumen::class, 'kategori_dokumen_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'kategori_dokumen_id');
    }
}
