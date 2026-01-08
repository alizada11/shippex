<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\ShippingBookingModel;
use App\Models\ShopperRequestModel;

class Payment extends BaseController
{
    public function index()
    {

        $data['client_id'] = env('paypal.' . env('paypal.mode', 'sandbox') . '.client_id');
        return view('payment/index', $data);
    }

    public function create_order()
    {
        $data = $this->request->getJSON(true);
        $amount = $data['amount'] ?? 0;

        $body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $amount
                ],
                "description" => "Test payment"
            ]],
            "application_context" => [
                "brand_name"   => "My Store",
                "user_action"  => "PAY_NOW",
                "return_url"   => base_url('payment/success'),
                "cancel_url"   => base_url('payment/cancel')
            ]
        ];

        $result = paypal_api_request('', 'POST', $body);

        if ($result['code'] === 201) {
            return $this->response->setJSON(['id' => $result['data']['id']]);
        }

        return $this->response->setJSON(['error' => $result['data']], 500);
    }

    public function capture_order()
    {
        $orderId = $this->request->getPost('orderID');
        $payFor = $this->request->getPost('payFor');

        $result = paypal_api_request("/{$orderId}/capture", 'POST');

        if ($result['code'] === 201) {
            if ($payFor === 'shopper') {
                $model = new ShopperRequestModel();
                $model->update($orderId, ['status' => 'processing', 'payment_status' => 'paid', 'payment_info' => json_encode($result['data'])]);
            }
            if ($payFor === 'shipping') {
                $model = new ShippingBookingModel();
                $model->update($orderId, ['status' => 'shipping', 'payment_status' => 'paid', 'payment_info' => json_encode($result['data'])]);
            }
            if ($payFor === 'package') {
                $model = new PackageModel();
                $model->update($orderId, ['payment_status' => 'paid', 'payment_info' => json_encode($result['data'])]);
            }

            // Success – save to DB here
            return $this->response->setJSON(['status' => 'COMPLETED']);
        }

        return $this->response->setJSON(['error' => $result['data']], 500);
    }

    public function success()
    {
        return view('payment/success');
    }
    public function cancel()
    {
        return view('payment/cancel');
    }
}
