<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The vehicle types that belong to the product for royalty
     */
    public function vehicleTypes()
    {
        return $this->belongsToMany('App\Models\VehicleType', 'royalty_chart', 'product_id', 'vehicle_type_id');
    }
}
