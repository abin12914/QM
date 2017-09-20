<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcavatorReading extends Model
{
    public $timestamps = false;
    protected $table = 'excavator_readings';

    /**
     * Get the transaction details associated with the excavator reading
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the excavator details associated with the excavator reading
     */
    public function excavator()
    {
        return $this->belongsTo('App\Models\Excavator','excavator_id');
    }

    /**
     * Get the operator details associated with the excavator reading
     */
    public function operator()
    {
        return $this->belongsTo('App\Models\Account','operator_account_id');
    }
}
