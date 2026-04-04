<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function pay(Order $order){
        return DB::transaction(function () use ($order){

            if($order->status !== 'pending'){
                return response()->json([
                    'message' => 'Order already paid or processed'
                ] , 400);
            }

             $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => Str::uuid(),
                'amount' => $order->total_price,
                'gateway' => 'internal',
                'payment_method' => 'card',
                'status' => 'paid',
                'paid_at' => now()
            ]);

            $order->update([
            'status' => 'paid'
            ]);

         return response()->json([
            'message' =>'Payment successful',
            'payment' => $payment
         ]);
        });

       

         

    }
    
}
