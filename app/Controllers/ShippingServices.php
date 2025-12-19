<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\ShippingBookingModel;
use App\Models\ShippingServiceModel;
use App\Models\UserModel;

class ShippingServices extends BaseController
{
 use ResponseTrait;
 protected $model;

 public function __construct()
 {
  helper('url');
  helper('form');
  helper('shipping_helper'); // ensure the parser helper is autoloaded or required
  $this->model = new ShippingServiceModel();
 }
 public function create_form()
 {

  return view('admin/shipping_import.php');
 }
 public function getAll($id)
 {

  $records = $this->model->where('request_id', $id)->findAll();

  if (empty($records)) {
   $records = [];
  }
  // If the request is AJAX → return JSON
  if ($this->request->isAJAX()) {
   return $this->response->setJSON([
    'status' => 'ok',
    'data' => $records
   ]);
  }

  // Otherwie → return a view

  return view('admin/shipping/rates', [
   'records' => $records,
   'title' => 'Rates',
   'requestId' => $id
  ]);
 }




 public function setPrice()
 {
  $request = $this->request->getJSON(true);

  if (!isset($request['service_id'], $request['request_id'])) {
   return $this->failValidationErrors('Service ID and Request ID are required.');
  }

  $serviceId = $request['service_id'];
  $requestId = $request['request_id'];

  $serviceModel = new ShippingServiceModel();
  $bookingModel = new ShippingBookingModel();

  // Find the shipping service
  $service = $serviceModel->find($serviceId);
  if (!$service) {
   return $this->failNotFound('Shipping service not found.');
  }

  // Find the booking
  $booking = $bookingModel->find($requestId);
  if (!$booking) {
   return $this->failNotFound('Shipping request not found.');
  }

  // Convert features JSON to comma-separated string
  $featuresArray = [];
  if (!empty($service['features'])) {
   $decoded = json_decode($service['features'], true);
   if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
    foreach ($decoded as $k => $v) {
     $featuresArray[] = "$k: $v";
    }
   } else {
    $featuresArray[] = $service['features']; // fallback
   }
  }
  $featuresString = implode(', ', $featuresArray);

  // Build description


  // Update booking fields
  $bookingData = [
   'courier_name'  => $service['provider_name'],
   'service_name'  => $service['service_name'],
   'delivery_time' => $service['transit_days'] . ' days',
   'total_charge'  => $service['price'],
   'description'   => $service['transit_text'],
   'currency'      => $service['currency'],
   'price_set'     => 1, // default to 1 if not set
  ];



  $bookingModel->update($requestId, $bookingData);

  return $this->respond([
   'success' => true,
   'message' => 'Shipping service has been applied to your booking.'
  ]);
 }
 // GET /shipping-services
 public function index()
 {
  $perPage = (int)$this->request->getGet('per_page') ?: 50;
  $page = (int)$this->request->getGet('page') ?: 1;

  $data = $this->model->orderBy('id', 'DESC')->paginate($perPage, 'default', $page);
  $pager = $this->model->pager;

  return $this->response->setJSON([
   'status' => 'ok',
   'data' => $data,
   'pager' => [
    'currentPage' => $pager->getCurrentPage(),
    'total' => $pager->getTotal(),
    'perPage' => $perPage,
   ]
  ]);
 }

 // GET /shipping-services/{id}
 public function show($id = null)
 {
  $item = $this->model->find($id);
  if (!$item) {
   return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Not found']);
  }
  // decode features if needed
  if (isset($item['features']) && $item['features'] !== null && is_string($item['features'])) {
   $item['features'] = json_decode($item['features'], true);
  }
  return $this->response->setJSON(['status' => 'ok', 'data' => $item]);
 }

 // POST /shipping-services
 public function create()
 {
  $input = $this->request->getJSON(true); // accept JSON body for single record
  if (!$input) {
   // fallback to form data
   $input = $this->request->getPost();
  }

  if (isset($input['features']) && is_array($input['features'])) {
   $input['features'] = json_encode($input['features']);
  }
  $requestId = $input['request_id'];

  $this->model->insert($input);
  $id = $this->model->getInsertID();
  if ($id) {

   $bookingModel = new ShippingBookingModel();
   $id = $input['request_id'];
   $bookingModel->update($id, ['set_rate' => 0]);

   $user_id = $bookingModel->select('user_id')->where('id', $id)->first();
   $user_id = $user_id['user_id'] ?? null; // now you have the actual user_id


   $userModel = new UserModel();

   // Fetch main request
   $user = $userModel->find($user_id);
   $userName = $user ? ($user['firstname'] . ' ' . $user['lastname']) : 'Customer';

   $data = [
    'userName' => $userName ?? 'Customer'
   ];
   $email = \Config\Services::email();
   $email->setFrom('info@shippex.online', 'Shippex Admin');
   $email->setTo($user['email']);
   $email->setSubject("We've Set The Shipping Prices");
   $data['reqLink'] = base_url("customer/shipping/details/" . $id);

   $message = view('emails/shipping_price_set', $data);


   $email->setMessage($message);
   $email->setMailType('html');
   $email->send();

   $title = "New Price Inserted";
   $actionDesc = "created";
   $modelName = "Shipping Service";
   $recordId = $id; // the inserted record ID
   $userName = session()->get('full_name');
   $adminLink = base_url("shipping/details/" . $id);

   send_admin_notification($actionDesc, $title, $modelName, $recordId, $userName, null, '', $adminLink);
  }
  return $this->response->setStatusCode(201)->setJSON(['status' => 'ok', 'id' => $id]);
 }

 public function manualInsert()
 {
  $request = $this->request->getPost();

  // Convert features (comma separated → JSON)
  $features = [];

  if (!empty($request['features'])) {
   $pairs = explode(',', $request['features']);
   foreach ($pairs as $pair) {
    if (strpos($pair, ':') !== false) {
     [$k, $v] = array_map('trim', explode(':', $pair, 2));
     $features[$k] = $v;
    }
   }
  }

  $serviceModel = new \App\Models\ShippingServiceModel();

  $data = [
   'provider_name' => $request['provider_name'],
   'provider_logo'  => $request['provider_logo'],
   'service_name'  => $request['service_name'],
   'price'         => $request['price'],
   'rating'         => 5,
   'currency'      => $request['currency'],
   'transit_text'  => $request['transit_text'],
   'transit_days'  => $request['transit_days'],
   'request_id'  => $request['request_id'],
   'features'      => json_encode($request['features']),
   'created_at'    => date('Y-m-d H:i:s')
  ];

  $serviceModel->insert($data);

  $inserted[] = $serviceModel->getInsertID();
  if ($inserted) {
   $bookingModel = new ShippingBookingModel();
   $id = $request['request_id'];
   $bookingModel->update($id, ['set_rate' => 0]);

   $user_id = $bookingModel->select('user_id')->where('id', $id)->first();
   $user_id = $user_id['user_id'] ?? null; // now you have the actual user_id


   $userModel = new UserModel();

   // Fetch main request
   $user = $userModel->find($user_id);
   $userName = $user ? ($user['firstname'] . ' ' . $user['lastname']) : 'Customer';

   $data = [
    'userName' => $userName ?? 'Customer'
   ];
   $email = \Config\Services::email();
   $email->setFrom('info@shippex.online', 'Shippex Admin');
   $email->setTo($user['email']);
   $email->setSubject("We've Set The Shipping Prices");
   $data['reqLink'] = base_url("customer/shipping/details/" . $id);

   $message = view('emails/shipping_price_set', $data);


   $email->setMessage($message);
   $email->setMailType('html');
   $email->send();

   $title = "New Price Inserted";
   $actionDesc = "created";
   $modelName = "Shipping Service";
   $recordId = $id; // the inserted record ID
   $userName = session()->get('full_name');
   $adminLink = base_url("shipping/details/" . $id);

   send_admin_notification($actionDesc, $title, $modelName, $recordId, $userName, null, '', $adminLink);
  }
  return $this->response->setJSON([
   'success' => true,
   'message' => 'Shipping service added successfully'
  ]);
 }


 // PUT /shipping-services/{id}
 public function update($id = null)
 {
  if (!$id) {
   return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Missing id']);
  }
  $input = $this->request->getJSON(true);
  if (!$input) {
   $input = $this->request->getRawInput();
  }
  if (isset($input['features']) && is_array($input['features'])) {
   $input['features'] = json_encode($input['features']);
  }

  $ok = $this->model->update($id, $input);
  $title = "New Price Inserted";
  $actionDesc = "created";
  $modelName = "Shipping Service";
  $recordId = $id; // the inserted record ID
  $userName = session()->get('full_name');
  $adminLink = base_url("customer/shipping/details/" . $id);

  send_admin_notification($actionDesc, $title, $modelName, $recordId, $userName, null, '', $adminLink);
  if ($ok === false) {
   return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Update failed']);
  }

  return $this->response->setJSON(['status' => 'ok', 'message' => 'updated']);
 }

 // DELETE /shipping-services/{id}
 public function delete($id = null)
 {
  if (!$id) {
   return $this->response->setStatusCode(400)->setJSON([
    'status' => 'error',
    'message' => 'Missing id'
   ]);
  }

  $ok = $this->model->delete($id);

  if (!$ok) {
   return $this->response->setStatusCode(400)->setJSON([
    'status' => 'error',
    'message' => 'Delete failed'
   ]);
  }

  return $this->response->setJSON([
   'status' => 'ok',
   'message' => 'deleted'
  ]);
 }



 public function importPreview($id)
 {
  $html = $this->request->getPost('html') ?? null;
  if (!$html) {
   $json = $this->request->getJSON(true);
   $html = $json['html'] ?? null;
  }
  if (!$html) return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'missing html']);

  $records = parse_shipping_services_html($html, $id);
  $preview = [];
  foreach ($records as $idx => $r) {
   list($ok, $errs) = validate_shipping_record($r);
   $preview[] = [
    'index' => $idx,
    'record' => $r,
    'valid' => $ok,
    'errors' => $errs,
   ];
  }

  return $this->response->setJSON(['status' => 'ok', 'count' => count($preview), 'preview' => $preview]);
 }

 public function importHtml($id)
 {
  $html = $this->request->getPost('html') ?? null;
  if (!$html) {
   $json = $this->request->getJSON(true);
   $html = $json['html'] ?? null;
  }
  if (!$html) return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'missing html']);

  $records = parse_shipping_services_html($html, $id);
  $inserted = [];
  $errors = [];
  foreach ($records as $idx => $r) {
   list($ok, $errs) = validate_shipping_record($r);
   if (!$ok) {
    $errors[] = ['index' => $idx, 'errors' => $errs, 'record' => $r];
    continue;
   }
   if (isset($r['features']) && !is_string($r['features'])) $r['features'] = json_encode($r['features']);
   $this->model->insert($r);
   $inserted[] = $this->model->getInsertID();
  }
  if ($inserted) {
   $bookingModel = new ShippingBookingModel();

   $bookingModel->update($id, ['set_rate' => 0]);

   $user_id = $bookingModel->select('user_id')->where('id', $id)->first();
   $user_id = $user_id['user_id'] ?? null; // now you have the actual user_id


   $userModel = new UserModel();

   // Fetch main request
   $user = $userModel->find($user_id);
   $userName = $user ? ($user['firstname'] . ' ' . $user['lastname']) : 'Customer';

   $data = [
    'userName' => $userName ?? 'Customer'
   ];
   $email = \Config\Services::email();
   $email->setFrom('info@shippex.online', 'Shippex Admin');
   $email->setTo($user['email']);
   $email->setSubject("We've Set The Shipping Prices");
   $data['reqLink'] = base_url("customer/shipping/details/" . $id);

   $message = view('emails/shipping_price_set', $data);


   $email->setMessage($message);
   $email->setMailType('html');
   $email->send();
  }
  return $this->response->setJSON(['status' => 'ok', 'inserted' => $inserted, 'errors' => $errors]);
 }

 public function importSingle($id)
 {
  // Read input
  $data = $this->request->getPost();
  if (!$data) {
   $data = $this->request->getJSON(true);
  }

  if (!$data) {
   return $this->response->setStatusCode(400)->setJSON([
    'status' => 'error',
    'message' => 'missing record'
   ]);
  }

  // Extract real shipping record
  $record = $data['record'] ?? $data;

  // Validate
  list($ok, $errs) = validate_shipping_record($record);
  if (!$ok) {
   return $this->response->setStatusCode(422)->setJSON([
    'status' => 'error',
    'errors' => $errs,
    'record' => $record
   ]);
  }

  // Encode features as JSON if needed
  if (isset($record['features']) && !is_string($record['features'])) {
   $record['features'] = json_encode($record['features']);
  }

  // Insert record into DB
  $this->model->insert($record);
  $insertId = $this->model->getInsertID();

  // Update booking
  $bookingModel = new ShippingBookingModel();
  $bookingModel->update($id, ['set_rate' => 0]);

  // Get user
  $booking = $bookingModel->select('user_id')->where('id', $id)->first();
  $userId = $booking['user_id'] ?? null;

  if ($userId) {
   $userModel = new UserModel();
   $user = $userModel->find($userId);

   if ($user) {
    $userName = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));
    if (!$userName) $userName = 'Customer';

    $dataEmail = [
     'userName' => $userName,
     'reqLink' => base_url("customer/shipping/details/" . $id)
    ];

    $email = \Config\Services::email();
    $email->setFrom('info@shippex.online', 'Shippex Admin');
    $email->setTo($user['email']);
    $email->setSubject("We've Set The Shipping Price");
    $email->setMessage(view('emails/shipping_price_set', $dataEmail));
    $email->setMailType('html');
    $email->send();

    $title = "New Price Inserted";
    $actionDesc = "created";
    $modelName = "Shipping Service";
    $recordId = $id; // the inserted record ID
    $userName = session()->get('full_name');
    $adminLink = base_url("customer/shipping/details/" . $id);

    send_admin_notification($actionDesc, $title, $modelName, $recordId, $userName, null, '', $adminLink);
   }
  }

  return $this->response->setJSON([
   'status' => 'ok',
   'inserted' => $insertId,
   'record' => $record
  ]);
 }
}
