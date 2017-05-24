<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Get the personal details related to the employee
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }
}
