<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * Get the vehicle type details of the vehicle
     */
    public function vehicleType()
    {
        return $this->belongsTo('App\Models\VehicleType');
    }
}
