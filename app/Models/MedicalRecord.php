<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_id', 
        'queue_id', 
        'diagnosis', 
        'action_taken', 
        'prescription',
        'id_rm',
        'visit_date',
        'polyclinic_id',
        'anamnese',
        'pemeriksaan_fisik',
        'keterangan'
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    public function polyclinic(): BelongsTo
    {
        return $this->belongsTo(Polyclinic::class);
    }
}
