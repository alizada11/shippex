<?php

namespace App\Controllers;

use App\Models\ShippingBookingModel;
use App\Models\ShopperItemModel;
use App\Models\ShopperRequestModel;
use App\Models\UserModel;
use App\Models\VirtualAddressModel;

class Admin extends BaseController
{
 public function index()
 {
  // if (get_cookie('remember_token')) {
  //  // Cookie exists
  //  $token = get_cookie('remember_token');
  //  echo "Cookie is set. Token: " . $token;
  // } else {
  //  // Cookie not set
  //  echo "No cookie found.";
  // }

  // exit;
  $userId = session()->get('user_id'); // adjust if you're using a different session key

  $addressModel = new VirtualAddressModel();
  $data['address'] = $addressModel->where('is_active', 1)->first();
  $data['orders'] = 0;
  $data['users'] = 0;
  $data['addresses'] = $addressModel->countAllResults();
  $data['usersCount'] = (new UserModel())->countAllResults();
  $data['users'] = (new UserModel())->findAll(5);
  $bookings = new ShippingBookingModel();
  $data['orders']  = $bookings->countAllResults();

  $data['title'] = 'Dashboard';
  return view('admin/dashboard', $data);
 }


 // shopper methods
 public function shopperRequests()
 {
  helper('app');



  $requestModel = new ShopperRequestModel();

  $data['requests'] = $requestModel->orderBy('created_at', 'DESC')->paginate(12);
  $data['title'] = 'Shopper Requests';
  $data['pager'] = $requestModel->pager;

  return view('admin/shopper/list', $data);
 }
 public function shopperView($id)
 {
  $requestModel = new ShopperRequestModel();
  $itemModel = new ShopperItemModel();

  // Fetch main request
  $request = $requestModel->find($id);
  if (!$request) {
   return redirect()->to('admin/shopper/requests')->with('error', 'Request not found.');
  }

  // Fetch related items
  $items = $itemModel->where('request_id', $id)->findAll();

  return view('admin/shopper/view', [
   'request' => $request,
   'items'   => $items,
   'title' => 'Request Details'
  ]);
 }

 public function set_price()
 {
  $requestId = $this->request->getPost('request_id');
  $newPrice  = $this->request->getPost('new_price');

  $requestModel = new \App\Models\ShopperRequestModel();
  $userModel    = new \App\Models\UserModel();

  $requestData = $requestModel->find($requestId);
  if (!$requestData) {
   return $this->response->setJSON(['success' => false, 'message' => 'Request not found']);
  }

  // Update request
  $requestModel->update($requestId, [
   'price'  => $newPrice,
   'status' => 'wait_for_payment',
  ]);

  // Send email
  $email = \Config\Services::email();

  // Prepare data for email
  $requestModel = new ShopperRequestModel();
  $itemModel = new ShopperItemModel();

  // Fetch main request
  $request = $requestModel->find($requestId);
  $items = $itemModel->where('request_id', $requestId)->findAll();
  $userName = $userModel->find($request['user_id'])['firstname'] . ' ' . $userModel->find($request['user_id'])['lastname'];
  $data = [
   'request' => $request, // the $request row
   'items'   => $items,   // array of items
   'userName' => $userName ?? 'Customer'
  ];

  $message = view('emails/request_wait_for_payment', $data);

  $email->setFrom('info@shippex.online', 'Shippex Admin');
  $email->setTo($userModel->find($request['user_id'])['email']);
  $email->setSubject('Your Request #' . $request['id'] . ' is Waiting for Payment');
  $email->setMessage($message);
  $email->setMailType('html'); // Important

  if ($email->send()) {
   return $this->response->setJSON(['success' => true]);
  } else {
   log_message('error', $email->printDebugger(['headers']));
   return $this->response->setJSON(['success' => false, 'message' => 'Email failed to send']);
  }
 }
}
