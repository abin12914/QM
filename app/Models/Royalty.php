<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Royalty extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
