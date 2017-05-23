<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * Get the personal record associated with the account
     */
    public function accountDetail()
    {
        return $this->hasone('App\Models\AccountDetail', 'account_id');
    }
}
