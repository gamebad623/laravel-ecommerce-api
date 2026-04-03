<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(){
        $orders = Order::with('items.product', 'user')
                ->latest()
                ->get();

        return OrderResource::collection($orders);
    }

    public function show(Order $order){
        return new OrderResource(
            $order->load('items.product', 'user')
        );
    }

    public function update(Request $request , Order $order){
        $request->validate([
            'status' => ['required' , 'in:pendig,paid,shipped,delivered,cancelled']
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return new OrderResource(
            $order->load('items.produc' , 'user')
        );
    }
}
