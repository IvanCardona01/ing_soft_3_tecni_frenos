<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'segment_id',
        'name',
        'quantity',
        'unit_value',
        'is_authorized',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_value' => 'decimal:2',
        'is_authorized' => 'boolean',
    ];

    public function segment(): BelongsTo
    {
        return $this->belongsTo(QuotationSegment::class, 'segment_id');
    }

    /**
     * Calcula el valor total del item (cantidad * valor unitario)
     */
    public function getTotalValueAttribute(): float
    {
        return $this->quantity * $this->unit_value;
    }
}




