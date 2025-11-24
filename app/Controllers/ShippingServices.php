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

  return $this->response->setJSON([
   'status' => 'ok',
   'data'   => $records
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
  $descriptionParts = [
   $service['transit_text'],
   $service['service_name']
  ];

  if (!empty($featuresString)) {
   $descriptionParts[] = $featuresString;
  }

  $description = implode(', ', $descriptionParts);


  // Update booking fields
  $bookingData = [
   'courier_name'  => $service['provider_name'],
   'service_name'  => $service['service_name'],
   'delivery_time' => $service['transit_days'],
   'total_charge'  => $service['price'],
   'description'   => $description,
   'set_rate'       => 0, // default to 1 if not set
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

  $this->model->insert($input);
  $id = $this->model->getInsertID();

  return $this->response->setStatusCode(201)->setJSON(['status' => 'created', 'id' => $id]);
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
   'service_name'  => $request['service_name'],
   'price'         => $request['price'],
   'currency'      => $request['currency'],
   'transit_text'  => $request['transit_text'],
   'transit_days'  => $request['transit_days'],
   'request_id'  => $request['request_id'],
   'features'      => json_encode($features, JSON_UNESCAPED_UNICODE),
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

  if ($ok === false) {
   return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Update failed']);
  }

  return $this->response->setJSON(['status' => 'ok', 'message' => 'updated']);
 }

 // DELETE /shipping-services/{id}
 public function delete($id = null)
 {
  if (!$id) {
   return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Missing id']);
  }
  $this->model->delete($id);
  return $this->response->setJSON(['status' => 'ok', 'message' => 'deleted']);
 }

 // POST /shipping-services/import-html
 // Accepts form-field `html` (textarea content) and saves multiple rows
 // public function importHtml()
 // {
 //  $html = $this->request->getPost('html');
 //  if (!$html) {
 //   $json = $this->request->getJSON(true);
 //   $html = $json['html'] ?? null;
 //  }
 //  if (!$html) {
 //   return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Missing html payload']);
 //  }

 //  $records = parse_shipping_services_html($html);
 //  $saved = [];
 //  foreach ($records as $rec) {
 //   // ensure features is JSON string
 //   if (isset($rec['features']) && !is_string($rec['features'])) {
 //    $rec['features'] = json_encode($rec['features']);
 //   }
 //   // insert
 //   $this->model->insert($rec);
 //   $saved[] = $this->model->getInsertID();
 //  }

 //  return $this->response->setJSON(['status' => 'ok', 'inserted_ids' => $saved, 'count' => count($saved)]);
 // }

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
}
