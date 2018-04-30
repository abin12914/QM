<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $dates = ['date_time', 'deleted_at'];

    /**
     * Get the transaction details associated with the sale
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    public function royalty()
    {
        return $this->hasOne('App\Models\Royalty');
    }

    /**
     * Get the vehicle details associated with the sale
     */
    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle','vehicle_id');
    }

    /**
     * Get the product details associated with the sale
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}
