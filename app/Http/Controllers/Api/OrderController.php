<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(){
        return DB::transaction(function(){ // this is like a package to make sure everything going to be successfull in the same time
            //getting the cart for the logged in user            
            $cart = Cart::with('items.product')
                ->where('user_id' , Auth::id())
                ->firstOrFail();
            //checking if the cart is empty
            if($cart->items->isEmpty()){
                return response()->json([
                    'message' => 'Cart is empty'
                ] , 400);
            }
            //calculate the total price of the items
            $total = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            //creating the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'status' => 'pending'
            ]);
            //copying the cart items to the order items
            foreach($cart->items as $item){
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'=> $item->quantity,
                    'price' => $item->product->price
                ]);
                //reduce the stock 
                $item->product->decrement('stock' , $item->quantity);
            }
            //clear cart
            $cart->items()->delete();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => new OrderResource($order->load('items.product'))
            ], 201);
        });
    }

    public function index(){
        $orders = Order::with('items.product')->where('user_id' , Auth::id())
                        ->latest()
                        ->get();

        return OrderResource::collection($orders);
    }

    public function show($id){
        $order = Order::with('items.product')
                ->where('user_id' , Auth::id())
                ->findOrFail($id);


        return new OrderResource($order);
    }
}
