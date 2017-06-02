<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $dates = ['date_time'];

    /**
     * Get the transaction details associated with the sale
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }
}
