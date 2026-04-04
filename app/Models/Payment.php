<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'gateway',
        'payment_method',
        'status',
        'paid_at'
    ];


    public function order(){
        return $this->belongsTo(Order::class);
    }
}
