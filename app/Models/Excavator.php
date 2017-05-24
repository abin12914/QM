<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excavator extends Model
{
    /**
     * Get the account details related to the excavator contractor
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account','contractor_account_id');
    }
}
