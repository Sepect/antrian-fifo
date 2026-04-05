<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Queue extends Model
{
    protected $fillable = [
        'patient_id', 
        'registered_by', 
        'queue_date', 
        'queue_number', 
        'booking_code', 
        'total_score', 
        'priority', 
        'status', 
        'screening_notes'
    ];

    protected $casts = [
        'queue_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function screeningItems(): HasMany
    {
        return $this->hasMany(QueueScreeningItem::class);
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
