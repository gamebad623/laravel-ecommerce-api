<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){
        $cart = Cart::with('items.product')->firstOrCreate([
            'user_id' => Auth::id()
        ]);

        return new CartResource($cart);
    }

    public function add(Request $request){
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = CartItem::where('cart_id' , $cart->id)
                        ->where('product_id' , $request->product_id)
                        ->first();

        if($item){

            $item->quantity += $request->quantity;
            $item->save();
 
        }else{
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        $cart->load('items.product');
        return new CartResource($cart);

    }

    public function update(Request $request, $itemId){
        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);
        $cart = $item->cart->load('items.product');
        return new CartResource($cart);
    }

    public function remove($itemId){
        CartItem::findOrFail($itemId)->delete();
        return response()->json(['message' => 'Item removed']);
    }
}
