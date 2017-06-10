<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $dates = ['date_time'];

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
