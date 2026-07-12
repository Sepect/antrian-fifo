<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'medical_record_number',
        'nik', 
        'name', 
        'gender', 
        'birth_date', 
        'address', 
        'phone', 
        'religion',
        'occupation',
        'user_id',
        'employee_id',
        'no_rm',
        'id_rm_p',
        'status_pasien',
        'keluarga_pegawai',
        'tempat_lahir',
        'keterangan'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Accessor: Hitung umur otomatis dari tanggal lahir.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return Carbon::parse($this->birth_date)->age;
    }

    /**
     * Accessor: Jenis kelamin dalam bentuk yang bisa dibaca.
     *
     * Data lama hasil import menyimpan "LAKI-LAKI"/"Perempuan", sedangkan
     * pendaftaran baru menyimpan "L"/"P". Samakan lewat huruf pertama.
     */
    public function getGenderLabelAttribute(): ?string
    {
        return match (strtoupper(substr((string) $this->gender, 0, 1))) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => null,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
