<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polyclinic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
