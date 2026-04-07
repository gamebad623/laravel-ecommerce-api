<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function pay(Order $order , PaymobService $paymob){
       
    $token = $paymob->authenticate();

    $paymobOrderId = $paymob->createOrder($token, $order);

    $paymentToken = $paymob->paymentKey($token, $paymobOrderId, $order);

    $url = "https://accept.paymob.com/api/acceptance/iframes/"
        . config('services.paymob.iframe_id')
        . "?payment_token={$paymentToken}";

        return response()->json([
            'payment_url' => $url
        ]);
    }

    public function webhook(Request $request){

        Log::info('PAYMOB WEBHOOK PAYLOAD', $request->all());

        return response()->json([
            'received' => true,
            'payload' => $request->all()
        ]);
        // $data = $request->all();
        // $merchantOrderId = $data['obj']['order']['merchant_order_id'] ?? null;
        // $success = $data['obj']['success'] ?? false;

        // if(!$merchantOrderId){
        //     return response()->json([
        //         'message'=> 'Missing order id'
        //     ] , 400);
        // }

        // $realOrderId = explode('-', $merchantOrderId)[0];

        // $order = Order::find($realOrderId);

        // if (!$order) {
        //     return response()->json(['message' => 'Order not found'], 404);
        // }

        // if ($success) {
        //     $order->update([
        //         'status' => 'paid'
        //     ]);
        // }

        // return response()->json([
        //     'message' => 'Webhook received'
        // ]);

    }
    
}
