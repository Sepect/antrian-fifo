<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip_lama', 'nip_baru', 'nama', 'nama_lengkap', 'jabatan',
        'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'pekerjaan',
        'alamat', 'agama', 'nama_pasangan', 'unit_pasangan', 'keterangan'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
