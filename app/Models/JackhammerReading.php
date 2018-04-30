<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JackhammerReading extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = false;
    protected $table = 'jackhammer_readings';

    /**
     * Get the transaction details associated with the jackhammer reading
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the jackhammer details associated with the jackhammer reading
     */
    public function jackhammer()
    {
        return $this->belongsTo('App\Models\Jackhammer','jackhammer_id');
    }
}
