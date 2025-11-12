<?php

namespace App\Controllers;

use App\Models\PackageModel;
use App\Models\ShopperRequestModel;
use App\Models\ShopperItemModel;
use App\Models\WarehouseRequestModel;
use CodeIgniter\Controller;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class Shopper extends Controller
{


  protected $shopperModel;
  protected $paypalClient;
  protected $token;

  public function __construct()
  {

    $this->token = getenv('EASYSHIP_API_TOKEN');
    if (!$this->token) {
      log_message('error', 'Easyship API token is not set.');
      throw new \Exception('Easyship API token is missing.');
    }
    $this->shopperModel = new ShopperRequestModel();
    $clientId = getenv('PAYPAL_CLIENT_ID');
    $clientSecret = getenv('PAYPAL_SECRET');
    $environment = new SandboxEnvironment($clientId, $clientSecret); // use LiveEnvironment for production
    $this->paypalClient = new PayPalHttpClient($environment);
  }
  protected $helpers = ['form', 'url', 'text'];

  public function index()
  {
    // show the form
    $data['title'] = 'Shopper Request';

    return view('shopper/form', $data);
  }

  public function submit()
  {
    $request = service('request');
    $session = session();

    if (! $this->validate([
      'name.*' => 'required|min_length[2]',
      'url.*'  => 'required|valid_url',
      'quantity.*' => 'required|integer|greater_than_equal_to[1]',
      'delivery_description' => 'permit_empty|min_length[2]',
      'delivery_notes' => 'permit_empty|min_length[2]',
    ])) {
      // validation failed
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // If "save for later" button used, it's is_saved=1
    $isSaved = $request->getPost('submit') === 'save';

    // create shopper request
    $reqModel = new ShopperRequestModel();
    $data = [
      'user_id' => $session->get('user_id') ?? null,
      'use_another_retailer' => $request->getPost('use_another_retailer') === 'yes' ? 1 : 0,
      'delivery_description' => $request->getPost('delivery_description'),
      'delivery_notes' => $request->getPost('delivery_notes'),
      'is_saved' => $isSaved ? 1 : 0,
      'status' => $isSaved ? 'saved' : 'pending',
    ];
    $reqId = $reqModel->insert($data);
    if (! $reqId) {
      return redirect()->back()->withInput()->with('error', 'Could not create request. Try again.');
    }

    // save items
    $itemModel = new ShopperItemModel();
    $names = $request->getPost('name');
    $urls  = $request->getPost('url');
    $sizes = $request->getPost('size');
    $colors = $request->getPost('color');
    $instructions = $request->getPost('instructions');
    $quantities  = $request->getPost('quantity');

    $itemsToInsert = [];
    for ($i = 0; $i < count($names); $i++) {
      $name = trim($names[$i]);
      if ($name === '') continue; // skip empty
      $itemsToInsert[] = [
        'request_id' => $reqId,
        'name' => esc($name),
        'url'  => esc($urls[$i] ?? null),
        'size' => esc($sizes[$i] ?? null),
        'color' => esc($colors[$i] ?? null),
        'instructions' => esc($instructions[$i] ?? null),
        'quantity' => (int) ($quantities[$i] ?? 1),
      ];
    }

    if (! empty($itemsToInsert)) {
      $itemModel->insertBatch($itemsToInsert);
    }

    // success
    $session->setFlashdata('success', $isSaved ? 'Request saved.' : 'Request submitted. We will contact you shortly.');
    return redirect()->to('/shopper/thank-you');
  }

  public function thankYou()
  {
    echo view('shopper/thank_you');
  }

  public function getItems()
  {
    $requestId = $this->request->getGet('request_id');
    $shopperItems = new \App\Models\ShopperItemModel();
    $items = $shopperItems->where('request_id', $requestId)->findAll();

    if (!$items) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'No items found']);
    }

    return $this->response->setJSON(['status' => 'success', 'items' => $items]);
  }

  public function myRequests()
  {
    $userId = session()->get('user_id');
    $requestModel = new \App\Models\ShopperRequestModel();

    $data['requests'] = $requestModel
      ->where('user_id', $userId)
      ->orderBy('created_at', 'DESC')
      ->paginate(12);
    $data['pager'] = $requestModel->pager;
    $data['title'] = 'Shopper Requests';
    return view('shopper/list', $data);
  }

  public function edit($id)
  {
    $requestModel = new \App\Models\ShopperRequestModel();
    $itemModel = new \App\Models\ShopperItemModel();

    $request = $requestModel->find($id);
    if (!$request) {
      return redirect()->back()->with('error', 'Request not found.');
    }

    $items = $itemModel->where('request_id', $id)->findAll();

    return view('shopper/edit', [
      'request' => $request,
      'items'   => $items,
      'title' => 'Edit Request'
    ]);
  }

  public function delete($id)
  {
    $requestModel = new \App\Models\ShopperRequestModel();
    $itemModel = new \App\Models\ShopperItemModel();

    // Check if request exists
    $request = $requestModel->find($id);
    if (! $request) {
      return redirect()->back()->with('error', 'Shopper request not found.');
    }

    // Delete all related items first
    $itemModel->where('request_id', $id)->delete();

    // Then delete the main request
    $requestModel->delete($id);

    return redirect()->to('/shopper/requests')->with('success', 'Shopper request deleted successfully.');
  }

  public function update($id)
  {
    $requestModel = new \App\Models\ShopperRequestModel();
    $itemModel = new \App\Models\ShopperItemModel();

    $data = $this->request->getPost();

    // ✅ Validation rules (simple and effective)
    $rules = [
      'name.*' => 'required|min_length[2]',
      'url.*'  => 'required|valid_url',
      'quantity.*' => 'required|integer|greater_than_equal_to[1]',
      'delivery_description' => 'required|in_list[Economy,Express]',
      'delivery_notes' => 'permit_empty|min_length[2]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $isSaved = $data['submit'] === 'save';

    // ✅ Update main request
    $requestModel->update($id, [
      'is_saved'             => $isSaved ? 1 : 0,
      'status'               => $isSaved ? 'saved' : 'pending',
      'delivery_description' => $data['delivery_description'],
      'delivery_notes'       => $data['delivery_notes'] ?? null,
      'use_another_retailer' => isset($data['use_another_retailer']) ? 'yes' : 'no',
    ]);

    // ✅ Replace old items
    $itemModel->where('request_id', $id)->delete();

    foreach ($data['name'] as $i => $name) {
      $itemModel->insert([
        'request_id'   => $id,
        'name'         => $name,
        'url'          => $data['url'][$i],
        'size'         => $data['size'][$i] ?? null,
        'color'        => $data['color'][$i] ?? null,
        'instructions' => $data['instructions'][$i] ?? null,
        'quantity'     => $data['quantity'][$i],
      ]);
    }

    return redirect()->to('/shopper/requests')->with('success', 'Request updated successfully.');
  }

  public function view($id)
  {
    $userId = session()->get('user_id'); // adjust if you're using a different session key
    $reqModel = new WarehouseRequestModel();
    $user_default_warehouse = $reqModel
      ->select('warehouse_requests.*, users.firstname, users.lastname, virtual_addresses.city, virtual_addresses.country')
      ->join('users', 'users.id = warehouse_requests.user_id')
      ->join('virtual_addresses', 'virtual_addresses.id = warehouse_requests.warehouse_id')
      ->orderBy('warehouse_requests.created_at', 'DESC')
      ->where('warehouse_requests.is_default', 1)
      ->where('warehouse_requests.user_id', $userId)
      ->first();

    $requestModel = new ShopperRequestModel();
    $itemModel = new ShopperItemModel();

    // Fetch main request
    $request = $requestModel->find($id);
    if (!$request) {
      return redirect()->to('/shopper/requests')->with('error', 'Request not found.');
    }

    // Fetch related items
    $items = $itemModel->where('request_id', $id)->findAll();

    return view('shopper/view', [
      'request' => $request,
      'items'   => $items,
      'title' => 'Request Details',
      'default_wh' => $user_default_warehouse
    ]);
  }


  public function markShipped()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $requestId = (int) $this->request->getPost('request_id');
    $retailer = $this->request->getPost('retailer');
    $trackingNumber = $this->request->getPost('tracking_number');
    $status = $this->request->getPost('status');

    $shopperRequestModel = new \App\Models\ShopperRequestModel();
    $warehouseRequestModel = new \App\Models\WarehouseRequestModel();
    $shopperItems = new \App\Models\ShopperItemModel();
    $packageModel = new \App\Models\PackageModel();

    $shopperRequest = $shopperRequestModel->find($requestId);
    if (!$shopperRequest) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Shopper request not found']);
    }

    $items = $shopperItems->where('request_id', $requestId)->findAll();
    if (empty($items)) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'No items found for this request.']);
    }

    $userId = $shopperRequest['user_id'];

    $defaultWarehouse = $warehouseRequestModel
      ->where('user_id', $userId)
      ->where('is_default', 1)
      ->where('status', 'accepted')
      ->first();

    if (!$defaultWarehouse) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'User has no default warehouse']);
    }

    // Update shopper request
    $shopperRequestModel->update($requestId, [
      'status'       => 'completed',
      'warehouse_id' => $defaultWarehouse['warehouse_id']
    ]);

    $createdPackages = [];
    $itemsData = $this->request->getPost('items'); // each item has id, weight, value, length, width, height

    foreach ($itemsData as $itemData) {
      $packageData = [
        'virtual_address_id' => $defaultWarehouse['warehouse_id'],
        'user_id'            => $userId,
        'retailer'           => $retailer,
        'height'             => (float)$itemData['height'],
        'width'              => (float)$itemData['width'],
        'length'             => (float)$itemData['length'],
        'weight'             => (float)$itemData['weight'],
        'value'              => (float)$itemData['value'],
        'tracking_number'    => $trackingNumber,
        'status'             => $status,
        'created_at'         => date('Y-m-d H:i:s')
      ];

      $inserted = $packageModel->insert($packageData);
      if ($inserted) {
        $createdPackages[] = $packageModel->getInsertID();
      }
    }


    if (count($createdPackages) > 0) {
      return $this->response->setJSON([
        'status'  => 'success',
        'message' => count($createdPackages) . ' package(s) created successfully.'
      ]);
    } else {
      return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Failed to create package.',
        'errors'  => $packageModel->errors(),
      ]);
    }
  }



  // payment

  // Endpoint for JS SDK createOrder
  public function createOrder($id)
  {
    $model = new ShopperRequestModel();
    $requestData = $model->find($id);

    if (!$requestData || $requestData['status'] !== 'wait_for_payment') {
      return $this->response->setJSON(['error' => 'Payment not allowed']);
    }

    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
      "intent" => "CAPTURE",
      "purchase_units" => [[
        "amount" => [
          "value" => $requestData['price'],
          "currency_code" => "USD"
        ]
      ]]
    ];

    try {
      $response = $this->paypalClient->execute($request);
      return $this->response->setJSON(['id' => $response->result->id]);
    } catch (\Exception $e) {
      return $this->response->setJSON(['error' => $e->getMessage()]);
    }
  }

  // Endpoint for JS SDK onApprove
  public function captureOrder($id)
  {
    $orderID = $this->request->getPost('orderID');
    if (!$orderID) {
      return $this->response->setJSON(['error' => 'Order ID missing']);
    }

    $request = new OrdersCaptureRequest($orderID);
    $request->prefer('return=representation');

    try {
      $response = $this->paypalClient->execute($request);

      // Update booking status
      $this->shopperModel->update($id, ['status' => 'processing', 'payment_status' => 'paid', 'payment_info' => json_encode($response->result)]);

      return $this->response->setJSON(['status' => 'success', 'details' => $response->result]);
    } catch (\Exception $e) {
      return $this->response->setJSON(['error' => $e->getMessage()]);
    }
  }


  public function complete($id)
  {
    $data = [
      'payment_status' => 'paid', // or 'completed'
      'status' => 'accepted'      // or whatever your workflow requires
    ];

    if ($this->shopperModel->update($id, $data)) {
      return redirect()->to(base_url('shipping/view/' . $id))
        ->with('success', 'Payment completed successfully.');
    } else {
      return redirect()->back()->with('error', 'Failed to update payment status.');
    }
  }

  public function cancel($id)
  {
    $data = [
      'payment_status' => 'canceled', // mark payment canceled
      'status' => 'canceled'          // mark order canceled
    ];

    if ($this->shopperModel->update($id, $data)) {
      return redirect()->to(base_url('shipping/view/' . $id))
        ->with('success', 'Payment has been canceled.');
    } else {
      return redirect()->back()->with('error', 'Failed to cancel payment.');
    }
  }
}
