<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\FaqModel;
use App\Models\ShippingBookingModel;
use App\Models\ShopperRequestModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\VirtualAddressModel;

class CustomersController extends BaseController
{
    public function dashboard()
    {
        $userId = session()->get('user_id'); // adjust if you're using a different session key

        $addressModel = new VirtualAddressModel();
        $address = $addressModel->findAll();
        $shopperRequests = new ShopperRequestModel();
        $shopperRequests = $shopperRequests->where('user_id', session()->get('user_id'))->countAllResults();
        $addresses = new AddressModel();
        $addresses = $addresses->where('user_id', session()->get('user_id'))->countAllResults();

        $booking = new ShippingBookingModel();
        $booking = $booking->where('user_id', session()->get('user_id'))->countAllResults();

        return view('customers/dashboard', [
            'address' => $address,
            'shopper_requests' => $shopperRequests,
            'addresses' => $addresses,
            'shipping_requests' => $booking
        ]);
    }
    public function requests()
    {
        helper('app');
        $model = new ShippingBookingModel();

        $data['requests'] = $model->where('user_id', session()->get('user_id'))->orderBy('created_at', 'DESC')->paginate(12);
        $data['pager'] = $model->pager;
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
        return view('customers/faq', $data);
    }
}
