<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    /**
     * Get the personal details related to the owner
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }

    /**
     * Get the user details related to the owner
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
