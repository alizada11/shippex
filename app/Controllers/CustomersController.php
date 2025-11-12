<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\FaqModel;
use App\Models\ShippingBookingModel;
use App\Models\ShopperRequestModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\VirtualAddressModel;
use App\Models\WarehouseRequestModel;

class CustomersController extends BaseController
{
    public function dashboard()
    {
        $userId = session()->get('user_id'); // adjust if you're using a different session key


        $addressModel = new VirtualAddressModel();
        $v_address = $addressModel->findAll();
        $shopperRequests = new ShopperRequestModel();
        $shopperRequests = $shopperRequests->where('user_id', $userId)->countAllResults();
        $addresses = new AddressModel();
        $addresses = $addresses->where('user_id', $userId)->countAllResults();



        $booking = new ShippingBookingModel();
        $booking = $booking->where('user_id', $userId)->countAllResults();

        $warehouseRequestModel = new warehouseRequestModel();
        $pending = $warehouseRequestModel->where('user_id', $userId)
            ->where('status', 'pending')
            ->findAll();
        $pendingMap = [];
        foreach ($pending as $req) {
            $pendingMap[$req['warehouse_id']] = $req['created_at'];
        }

        $userRequests = $warehouseRequestModel
            ->where('user_id', $userId)
            ->findAll();
        $requestMap = [];
        foreach ($userRequests as $req) {
            $requestMap[$req['warehouse_id']] = $req; // contains status, is_default, rejectation_reason
        }

        return view('customers/dashboard', [
            'address' => $v_address,
            'shopper_requests' => $shopperRequests,
            'addresses' => $addresses,
            'shipping_requests' => $booking,
            'pendingMap' => $pendingMap,
            'requestMap' => $requestMap,
            'title' => 'Customer Dashboard'
        ]);
    }
    public function requests()
    {
        helper('app');
        $model = new ShippingBookingModel();

        $data['requests'] = $model->where('user_id', session()->get('user_id'))->orderBy('created_at', 'DESC')->paginate(12);
        $data['pager'] = $model->pager;
        $data['title'] = "Shipment Requests";
        return view('customers/shipping/index', $data);
    }

    public function details($id)
    {
        helper('app');

        $historyModel = new \App\Models\BookingStatusHistoryModel();
        $model = new ShippingBookingModel();
        $data['request'] = $model->where('id', $id)->first();
        $data['history'] = $historyModel
            ->where('book_id', $id)
            ->orderBy('changed_at', 'AESC')
            ->findAll();
        $data['title'] = 'Shipping Details';
        return view('customers/shipping/details', $data);
    }

    public function destroy($id)
    {
        $shippingModel = new ShippingBookingModel();

        $shipping = $shippingModel->find($id);
        if (! $shipping) {
            return redirect()->back()->with('error', 'Shipping request not found.');
        }

        $shippingModel->delete($id);

        return redirect()->back()->with('success', 'Shipping request deleted successfully.');
    }

    public function faqs()
    {
        $faqModel = new FaqModel();
        $data['faqs'] = $faqModel->orderBy('id', 'ASC')->findAll();
        $data['title'] = 'FAQs';
        return view('customers/faq', $data);
    }
}
