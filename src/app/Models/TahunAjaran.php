<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'tahun_ajarans';

    protected $fillable = [
        'tahun',
        'status',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
