<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $dates = ['date_time', 'deleted_at'];

    /**
     * Get the transaction details associated with the sale
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }


    /**
     * Get the product details associated with the sale
     */
    public function purchasebleProduct()
    {
        return $this->belongsTo('App\Models\PurchasebleProduct','product_id');
    }
}
