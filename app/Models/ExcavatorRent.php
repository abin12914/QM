<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExcavatorRent extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = false;
    protected $table = 'excavator_rent';

    /**
     * Get the excavator details related to the rent record
     */
    public function excavator()
    {
        return $this->belongsTo('App\Models\Excavator', 'excavator_id');
    }

    /**
     * Get the transaction details associated with the excavator rent
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }
}
