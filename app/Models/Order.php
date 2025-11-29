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
        'status_id',
        'driver_name',
        'driver_phone',
        'driver_email',
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

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }
}

