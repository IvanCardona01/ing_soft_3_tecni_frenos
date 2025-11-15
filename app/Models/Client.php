<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'document_type',
        'document_number',
        'phone',
        'address',
        'driver_name',
        'driver_phone',
        'email',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}

