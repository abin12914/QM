<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcavatorRent extends Model
{
    public $timestamps = false;
    protected $table = 'excavator_rent';

    /**
     * Get the excavator details related to the rent record
     */
    public function excavator()
    {
        return $this->belongsTo('App\Models\Excavator', 'excavator_id');
    }
}
