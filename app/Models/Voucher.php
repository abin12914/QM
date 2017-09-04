<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    
    /**
     * Get the transaction details associated with the voucher
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the excavator details associated with the voucher
     */
    public function excavator()
    {
        return $this->belongsTo('App\Models\Excavator','excavator_id');
    }

    /**
     * Get the jackhammer details associated with the voucher
     */
    public function jackhammer()
    {
        return $this->belongsTo('App\Models\Jackhammer','jackhammer_id');
    }
}
