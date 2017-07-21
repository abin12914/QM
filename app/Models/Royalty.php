<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Royalty extends Model
{
    protected $table = 'royalty';
    public $timestamps = false;
    
    /**
     * Get the transaction details associated with the sale
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the sale details associated with the royalty
     */
    public function sale()
    {
        return $this->belongsTo('App\Models\Sale','sale_id');
    }

    /**
     * Get the vehicle details associated with the sale
     */
    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle','vehicle_id');
    }
}
