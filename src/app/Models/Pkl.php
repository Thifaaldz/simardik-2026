<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pkl extends Model
{
    // Table name should match the migration (plural)
    protected $table = 'pkls';

    protected $fillable = [
        'student_id',
        'dudi_id',
        'pembimbing_id',
        'periode_mulai',
        'periode_selesai',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function dudi()
    {
        return $this->belongsTo(Dudi::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pegawai::class, 'pembimbing_id');
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
