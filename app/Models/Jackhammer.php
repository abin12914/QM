<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jackhammer extends Model
{
    /**
     * Get the account details related to the jackhammer contractor
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account','contractor_account_id');
    }
}
