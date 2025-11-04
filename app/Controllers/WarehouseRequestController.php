<?php

namespace App\Controllers;

use App\Models\WarehouseRequestModel;
use App\Models\WarehouseModel;
use App\Models\UserModel;
use App\Models\VirtualAddressModel;

class WarehouseRequestController extends BaseController
{
 protected $warehouseRequest;
 protected $warehouse;
 protected $user;
 protected $virtual_address;

 public function __construct()
 {
  $this->warehouseRequest = new WarehouseRequestModel();
  $this->warehouse       = new WarehouseModel();
  $this->virtual_address       = new VirtualAddressModel();
  $this->user            = new UserModel();
 }

 // List all requests (for admin)
 public function index()
 {
  $data['requests'] = $this->warehouseRequest
   ->select('warehouse_requests.*, users.firstname, users.lastname,virtual_addresses.id as wh_id , users.id as user_id, virtual_addresses.city, virtual_addresses.country')
   ->join('users', 'users.id = warehouse_requests.user_id')
   ->join('virtual_addresses', 'virtual_addresses.id = warehouse_requests.warehouse_id')
   ->orderBy('warehouse_requests.created_at', 'DESC')
   ->findAll();

  return view('admin/warehouse_requests/index', $data);
 }


 // AJAX: Create a warehouse request
 public function requestWarehouse()
 {
  if (!$this->request->isAJAX()) {
   return redirect()->back();
  }

  $userId = session()->get('user_id');
  $warehouseId = (int) $this->request->getPost('warehouse_id');

  // Check if warehouse exists
  $warehouse = $this->virtual_address->find($warehouseId);
  if (!$warehouse) {
   return $this->response->setJSON([
    'status' => 'error',
    'message' => 'Invalid warehouse selected.'
   ]);
  }

  // ✅ Prevent duplicate requests for the same warehouse
  $existing = $this->warehouseRequest
   ->where('user_id', $userId)
   ->where('warehouse_id', $warehouseId)
   ->first(); // checks any status (pending, accepted, rejected)

  if ($existing) {
   return $this->response->setJSON([
    'status' => 'error',
    'message' => 'You have already requested this warehouse.'
   ]);
  }

  // Determine if this should be default
  $hasDefault = $this->warehouseRequest
   ->where('user_id', $userId)
   ->where('is_default', 1)
   ->first();

  $isDefault = $hasDefault ? 0 : 1; // First-ever request can be default

  // Save new request
  $this->warehouseRequest->save([
   'user_id' => $userId,
   'warehouse_id' => $warehouseId,
   'status' => 'accepted',
   'is_default' => $isDefault
  ]);

  return $this->response->setJSON([
   'status' => 'success',
   'message' => 'Warehouse request submitted successfully.'
  ]);
 }


 public function setDefault()
 {
  if (!$this->request->isAJAX()) {
   return redirect()->back();
  }

  $userId = session()->get('user_id');
  $warehouseId = (int) $this->request->getPost('warehouse_id');

  // Make sure the request exists and is accepted
  $request = $this->warehouseRequest
   ->where('user_id', $userId)
   ->where('warehouse_id', $warehouseId)
   ->where('status', 'accepted')
   ->first();

  if (!$request) {
   return $this->response->setJSON([
    'status' => 'error',
    'message' => 'Cannot set this warehouse as default. It is not accepted yet.'
   ]);
  }

  // Unset previous default
  $this->warehouseRequest
   ->where('user_id', $userId)
   ->set(['is_default' => 0])
   ->update();

  // Set this warehouse as default
  $this->warehouseRequest
   ->update($request['id'], ['is_default' => 1]);

  return $this->response->setJSON([
   'status' => 'success',
   'message' => 'Warehouse has been set as default.'
  ]);
 }


 // User: list their own warehouse requests
 public function myRequests()
 {
  $userId = session()->get('user_id');

  $requests = $this->warehouseRequest
   ->select('warehouse_requests.*, virtual_addresses.city, virtual_addresses.country')
   ->join('virtual_addresses', 'virtual_addresses.id = warehouse_requests.warehouse_id')
   ->where('user_id', $userId)
   ->orderBy('created_at', 'DESC')
   ->findAll();

  return view('customers/warehouse_requests/my_requests', ['requests' => $requests]);
 }



 // Admin: edit request (accept/reject)
 public function edit($id)
 {
  $data['request'] = $this->warehouseRequest->find($id);
  return view('admin/warehouse_requests/edit', $data);
 }

 public function update($id)
 {
  $status = $this->request->getPost('status');
  $reason = $this->request->getPost('rejectation_reason');

  $this->warehouseRequest->update($id, [
   'status' => $status,
   'rejectation_reason' => $status === 'rejected' ? $reason : null,
  ]);

  return redirect()->to('/warehouse-requests')->with('success', 'Request updated successfully.');
 }

 // Delete request (optional)
 public function delete($id)
 {
  $this->warehouseRequest->delete($id);
  return redirect()->to('/warehouse-requests')->with('success', 'Request deleted.');
 }
}
