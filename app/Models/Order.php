<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio_number',
        'user_id',
        'vehicle_id',
        'ingreso_en_grua',
        'testigos',
        'gasolina',
        'danos_preexistentes',
    ];

    protected $casts = [
        'ingreso_en_grua' => 'boolean',
        'testigos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

