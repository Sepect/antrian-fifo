<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McuRecord extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'queue_id', 'tgl_mcu', 'hasil_mcu'];

    protected $casts = [
        'tgl_mcu' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
