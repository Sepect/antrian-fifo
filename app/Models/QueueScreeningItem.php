<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueScreeningItem extends Model
{
    protected $fillable = [
        'queue_id', 
        'screening_id'
    ];

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }
}
