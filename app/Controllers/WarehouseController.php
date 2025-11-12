<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VirtualAddressModel;
use App\Models\UserModel;
use App\Models\WarehouseModel;

class WarehouseController extends BaseController
{
 protected $addressModel;
 protected $userModel;
 protected $token;
 protected $warehouseModel;
 public function __construct()
 {

  $this->warehouseModel = new WarehouseModel();
  $this->addressModel = new VirtualAddressModel();
  $this->userModel = new \App\Models\UserModel(); // Assumes you have a UserModel
  $this->token = getenv('EASYSHIP_API_TOKEN');
  if (!$this->token) {
   log_message('error', 'Easyship API token is not set.');
   throw new \Exception('Easyship API token is missing.');
  }
 }

 public function index()
 {
  $data['addresses'] = $this->addressModel->orderBy('id', 'DESC')->findAll();
  return view('admin/warehouses/index', $data);
 }

 public function create()
 {
  $data['users'] = $this->userModel->findAll();
  $data['title'] = 'Create a Warehouse';
  return view('admin/warehouses/create', $data);
 }

 public function store()
 {
  $data = $this->request->getPost();

  // Validation rules
  $rules = [
   'code'            => 'required|max_length[16]',
   'country'         => 'required|max_length[100]',
   'city'            => 'required|max_length[255]',
   'address_line_1'  => 'required|max_length[255]',
   'address_line_2'  => 'permit_empty|max_length[255]',
   'state'           => 'required|max_length[255]',
   'address_line'    => 'permit_empty',
   'map_link'        => 'permit_empty|valid_url',
   'postal_code'     => 'required|max_length[20]',
   'phone'           => 'permit_empty|max_length[20]',
   'is_active'       => 'required|in_list[0,1]',
  ];

  if (! $this->validate($rules)) {
   return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
  }

  try {
   $this->addressModel->save($data);
   return redirect()->to('/warehouse')->with('success', 'Warehouse address added successfully.');
  } catch (\Exception $e) {
   log_message('error', 'Warehouse address save error: ' . $e->getMessage());
   return redirect()->back()->withInput()->with('error', 'Failed to save warehouse address. Try again.');
  }
 }


 public function edit($id)
 {
  $data['address'] = $this->addressModel->find($id);
  $data['users'] = $this->userModel->findAll();
  $data['title'] = 'Edit warehouse';
  return view('admin/warehouses/edit', $data);
 }

 public function update($id)
 {
  $data = $this->request->getPost();

  // If checkbox not in post data, set it manually
  $data['is_active'] = $this->request->getPost('is_active') ? 1 : 0;

  $this->addressModel->update($id, $data);

  return redirect()->to('/warehouse')->with('success', 'Warehouse address updated successfully.');
 }


 public function delete($id)
 {
  $this->addressModel->delete($id);
  return redirect()->to('/warehouse')->with('success', 'Warehouse address deleted.');
 }


 public function view($code = null)
 {
  $addressesModel = new \App\Models\VirtualAddressModel();
  $addresses = $addressesModel->findAll();

  $allowed = [];

  foreach ($addresses as $adr) {
   $allowed[] = $adr['code'];   // ✅ appends to the array
  }

  // Or alternatively:
  // $allowed = array_column($addresses, 'code');


  if (!$code || !in_array($code, $allowed)) {
   // Show 404 if code is not allowed
   throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Warehouse not found");
  }


  $client = new \GuzzleHttp\Client();

  $response = $client->request('GET', 'https://public-api.easyship.com/2024-09/item_categories', [
   'headers' => [
    'Authorization' => 'Bearer ' . trim($this->token),
    'Content-Type'  => 'application/json',
    'Accept'        => 'application/json; version=2024-09',
   ],
  ]);

  // Decode JSON into associative array
  $data = json_decode($response->getBody(), true);

  // Extract categories
  $categories = $data['item_categories'] ?? [];
  // Convert code to view file name
  $viewFile = $code . '-warehouse';
  $title = 'Warehouse Details';
  // Load the view from app/Views/warehouse/
  return view('warehouses/' . $viewFile, compact('categories', 'title'));
 }

 public function show($countryCode)
 {

  $client = new \GuzzleHttp\Client();

  $response = $client->request('GET', 'https://public-api.easyship.com/2024-09/item_categories', [
   'headers' => [
    'Authorization' => 'Bearer ' . trim($this->token),
    'Content-Type'  => 'application/json',
    'Accept'        => 'application/json; version=2024-09',
   ],
  ]);

  // Decode JSON into associative array
  $data = json_decode($response->getBody(), true);

  // Extract categories
  $data['categories'] = $data['item_categories'] ?? [];

  $data['warehouse'] = $this->warehouseModel
   ->where('country_code', strtoupper($countryCode))
   ->first();

  if (!$data['warehouse']) {
   throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  }
  $data['title'] = $countryCode . ' Warehouse';
  return view('warehouses/show', $data);
 }
}
