<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;



class PaymobService{
    protected string $baseUrl = 'https://accept.paymob.com/api';

    public function authenticate(){

        $response = Http::post($this->baseUrl . '/auth/tokens' , [
                'api_key'=> config('services.paymob.api_key')
        ]);

            // dd($response->status(), $response->json());

        return $response->json()['token']; 
    }
    public function createOrder($token, $order)
    {
    $response = Http::post($this->baseUrl . '/ecommerce/orders', [
        'auth_token' => $token,
        'delivery_needed' => false,
        'amount_cents' => $order->total_price * 100,
        'currency' => 'EGP',
        'merchant_order_id' => $order->id . '-' . time(),
        'items' => []
    ]);

    // dd($response->status(), $response->json());
    return $response->json()['id'];

}

public function paymentKey($token, $paymobOrderId, $order)
{
    $response = Http::post($this->baseUrl . '/acceptance/payment_keys', [
        'auth_token' => $token,
        'amount_cents' => $order->total_price * 100,
        'expiration' => 3600,
        'order_id' => $paymobOrderId,
        'billing_data' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone_number' => '01000000000',
            'country' => 'EG',
            'city' => 'Cairo',
            'street' => 'Test Street',
            'building' => '1',
            'floor' => '1',
            'apartment' => '1',
        ],
        'currency' => 'EGP',
        'integration_id' => config('services.paymob.integration_id')
    ]);

    return $response->json()['token'];
}

}

?>