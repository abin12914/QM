<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    /**
     * The product that belong to the vehicle types for royalty
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'royalty_chart', 'vehicle_type_id', 'product_id')->withPivot('amount', 'status');
    }
}