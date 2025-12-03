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

    /**
     * Accessor para obtener la placa del vehículo.
     */
    public function getPlateAttribute(): string
    {
        return $this->vehicle->plate ?? 'N/A';
    }

    /**
     * Accessor para obtener el nombre del cliente.
     */
    public function getCustomerNameAttribute(): string
    {
        return $this->vehicle->client->full_name ?? 'N/A';
    }

    /**
     * Accessor para obtener el modelo del vehículo formateado.
     */
    public function getFormattedVehicleModelAttribute(): string
    {
        if (!$this->vehicle) {
            return 'N/A';
        }

        $parts = array_filter([
            strtoupper($this->vehicle->brand ?? ''),
            strtoupper($this->vehicle->model ?? ''),
            $this->vehicle->year ?? '',
        ]);

        return !empty($parts) ? implode(' ', $parts) : 'N/A';
    }

    /**
     * Accessor para obtener la fecha formateada.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Accessor para obtener el nombre del estado.
     */
    public function getStatusNameAttribute(): string
    {
        return $this->status->name ?? 'Sin estado';
    }
}

