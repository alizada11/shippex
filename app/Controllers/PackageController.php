<?php

namespace App\Controllers;

use App\Models\PackageActionModel;
use App\Models\PackageFileModel;
use App\Models\PackageItemModel;
use App\Models\PackageModel;
use App\Models\UserModel;
use App\Models\WarehouseModel;
use App\Models\VirtualAddressModel;
use CodeIgniter\Controller;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class PackageController extends BaseController
{
  protected $token;

  public function __construct()
  {

    $this->token = getenv('EASYSHIP_API_TOKEN');
    if (!$this->token) {
      log_message('error', 'Easyship API token is not set.');
      throw new \Exception('Easyship API token is missing.');
    }
  }
  public function index($id)
  {
    $session = session();
    $role = $session->get('role');       // 'admin' or 'customer'
    $userId = $session->get('user_id');  // logged-in user id

    $packageModel = new PackageModel();
    $itemModel = new PackageItemModel();
    $fileModel = new PackageFileModel();
    $actionModel = new PackageActionModel();

    // Number of records per page
    $perPage = 10;

    // Base query with join
    $packageModel->select('packages.*, virtual_addresses.country as warehouse_name, virtual_addresses.postal_code')
      ->join('virtual_addresses', 'virtual_addresses.id = packages.virtual_address_id', 'left')
      ->orderBy('packages.created_at', 'DESC');

    // If the logged-in user is not admin, restrict to their own packages
    if ($role !== 'admin') {
      $packageModel->where('packages.user_id', $userId);
    }

    // Optional: filter by virtual address id if needed
    if ($id) {
      $packageModel->where('virtual_addresses.id', $id);
    }

    // Pagination
    $data['packages'] = $packageModel->paginate($perPage);
    $data['pager'] = $packageModel->pager;

    // If no packages found
    if (!$data['packages']) {
      $data['packages'] = [];
    }

    // Optional: load specific package details (if $id refers to package id)
    $data['package'] = $packageModel->find($id);
    $data['items'] = $itemModel->getByPackage($id);
    $data['files'] = $fileModel->getFilesByPackage($id);
    $data['actions'] = $actionModel->getHistory($id);
    $data['virtual_address_id'] = $id;
    $data['title'] = 'Packages';
    // Render the same layout for everyone
    return view('admin/packages/index', $data);
  }


  public function create($id)
  {
    $session = session();
    $role = $session->get('role');
    // if ($role !== 'admin') {
    //   return redirect()->back()->with('error', 'You does not have permission to create a package!');
    // }
    $addressModel = new VirtualAddressModel();
    $userModel = new UserModel();

    $data['users'] = $userModel->findAll();
    $data['warehouses'] = $addressModel->findAll();
    $data['virtual_address_id'] = $id;
    $data['title'] = 'Create a Package';
    return view('admin/packages/create', $data);
  }

  public function store()
  {

    $session = session();
    $role = $session->get('role');
    // if ($role !== 'admin') {
    //   return redirect()->back()->with('error', 'You does not have permission to create a package!');
    // }
    $packageModel = new PackageModel();
    $itemModel = new PackageItemModel();
    $fileModel = new PackageFileModel();

    // Validate basic package info
    $rules = [
      'retailer' => 'required|min_length[2]|max_length[255]',
      // 'tracking_number' => 'required|min_length[3]|max_length[255]',
      'status' => 'required|in_list[incoming,ready,shipped,returned]'
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('error', 'Please correct errors')->with('validation', $this->validator);
    }

    // Save package
    $packageData = [
      'retailer' => $this->request->getPost('retailer'),
      // 'tracking_number' => 'PENDING-' . strtoupper(uniqid()),
      'user_id' => $this->request->getPost('user_id'),
      'virtual_address_id' => $this->request->getPost('warehouse_id'),
      'weight' => $this->request->getPost('weight') ?: '',
      'value'  => $this->request->getPost('value') ?: '',
      'length' => $this->request->getPost('length') ?: '',
      'width'  => $this->request->getPost('width') ?: '',
      'height' => $this->request->getPost('height') ?: '',
      'status' => $this->request->getPost('status'),
      'tracking_number' => $this->request->getPost('tracking_number') ?? null,
      'storage_days' => 30,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    // dd($packageData);
    $packageId = $packageModel->insert($packageData);

    if (!$packageId) {
      // Get validation or database errors
      $errors = $packageModel->errors(); // Validation errors
      $dbError = $packageModel->db->error(); // DB-level errors (like wrong column, constraint)

      return redirect()->back()->withInput()->with('error', 'There was an error while creating package')
        ->with('validation', $errors)
        ->with('dbError', $dbError);
    }

    // Save items
    $items = $this->request->getPost('items');
    if ($items) {
      foreach ($items as $item) {
        $itemModel->insert([
          'package_id' => $packageId,
          'quantity' => $item['quantity'] ?? 0,
          'description' => $item['description'] ?? null,
          'hs_code' => $item['hs_code'] ?? null,
          'weight' => $item['weight'] ?? null,
          'value' => $item['value'] ?? null,
        ]);
      }
    }

    // Save files
    $files = $this->request->getFiles();
    if ($packageId && $files && isset($files['files'])) {
      foreach ($files['files'] as $index => $fileArr) {
        $file = $fileArr['file'];
        if ($file && $file->isValid()) {
          $newName = $file->getRandomName();
          $file->move(FCPATH . 'uploads/packages/', $newName);

          $fileModel->insert([
            'package_id' => $packageId,
            'file_path' => 'uploads/packages/' . $newName,
            'file_name' => $file->getClientName(),
            'file_type' => $this->request->getPost("files[$index][file_type]"),
            'uploaded_by' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
          ]);
        }
      }
    }

    return redirect()->to(base_url("packages/show/$packageId"))
      ->with('success', 'Package created successfully!');
  }


  public function show($id)
  {

    $packageModel = new PackageModel();
    $itemModel = new PackageItemModel();
    $fileModel = new PackageFileModel();
    $actionModel = new PackageActionModel();

    $data['pkg'] = $packageModel->find($id);

    $createdAt = new DateTime($data['pkg']['created_at']);
    $now = new DateTime();
    $daysPassed = $createdAt->diff($now)->days;

    $freeDays = $data['pkg']['storage_days'] ?? 30;
    $overdueFee = 0;

    // Only calculate overdue fee if free days are exceeded
    if ($daysPassed > $freeDays) {
      $overdueDays = $daysPassed - $freeDays;
      $overdueFee = $overdueDays * 0.5;

      // Update the separate over_due_fee field
      $packageModel->update($data['pkg']['id'], ['over_due_fee' => $overdueFee]);
    }

    // Re-fetch package for view
    $data['package'] = $packageModel->find($id);
    $data['items']   = $itemModel->getByPackage($id);
    $data['files']   = $fileModel->getFilesByPackage($id);
    $data['actions'] = $actionModel->getHistory($id);
    $data['title']   = 'Package Details';

    // Pass to view
    $data['over_due'] = $overdueFee;


    $data['package'] = $packageModel->find($id);
    $data['items'] = $itemModel->getByPackage($id);
    $data['files'] = $fileModel->getFilesByPackage($id);
    $data['actions'] = $actionModel->getHistory($id);
    $data['title'] = 'Package Details';
    $data['over_due'] = $overdueFee;
    return view('admin/packages/show', $data);
  }

  public function edit($id)
  {
    $packageModel = new PackageModel();
    $warehouseModel = new WarehouseModel();
    $addressModel = new VirtualAddressModel();
    $itemModel = new PackageItemModel();
    $fileModel = new PackageFileModel();
    $userModel = new UserModel();
    $data['package'] = $packageModel->find($id);
    $data['warehouses'] = $warehouseModel->findAll();
    $data['addresses'] = $addressModel->findAll();
    $data['items'] = $itemModel->where('package_id', $id)->findAll();
    $data['files'] = $fileModel->where('package_id', $id)->findAll();
    $data['users'] = $userModel->findAll();
    $data['title'] = 'Edit Package';

    return view('admin/packages/edit', $data);
  }

  public function update($id)
  {
    $packageModel = new PackageModel();
    $actionModel  = new PackageActionModel();

    // Basic validation
    $rules = [
      'retailer'        => 'required|min_length[2]|max_length[255]',
      'tracking_number' => 'required|min_length[3]|max_length[255]',
      'weight'          => 'permit_empty|decimal',
      'value'           => 'permit_empty|decimal',
      'dimensions'      => 'permit_empty|string',
      'status'          => 'required|in_list[incoming,ready,shipped,returned]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()
        ->with('error', 'Please correct the highlighted errors.')
        ->with('validation', $this->validator);
    }

    // Collect and sanitize input
    $data = [
      'retailer'        => $this->request->getPost('retailer'),
      'tracking_number' => $this->request->getPost('tracking_number'),
      'weight'          => $this->request->getPost('weight'),
      'value'           => $this->request->getPost('value'),
      'dimensions'      => $this->request->getPost('dimensions'),
      'height'          => $this->request->getPost('height'),
      'width'          => $this->request->getPost('width'),
      'length'          => $this->request->getPost('length'),
      'status'          => $this->request->getPost('status'),
      'updated_at'      => date('Y-m-d H:i:s'),
    ];

    // Run the update
    if ($packageModel->update($id, $data)) {
      // Record an action log
      $userId = session()->get('user_id');
      $actionModel->insert([
        'package_id'   => $id,
        'action'       => 'update',
        'notes'        => 'Package details updated',
        'performed_by' => $userId,
        'created_at'   => date('Y-m-d H:i:s'),
      ]);

      return redirect()->to(base_url("packages/show/$id"))
        ->with('success', 'Package updated successfully.');
    }

    return redirect()->back()->with('error', 'Failed to update package.');
  }


  public function delete($id)
  {
    $packageModel = new PackageModel();
    $pkg = $packageModel->where('id', $id)->first();

    if (!$pkg) {
      if ($this->request->isAJAX()) {
        return $this->response->setJSON([
          'status' => 'error',
          'message' => 'Package not found.'
        ]);
      }
      return redirect()->back()->with('error', 'Package not found.');
    }

    $whId = $pkg['virtual_address_id'];

    // Perform deletion
    $packageModel->delete($id);

    // ✅ If AJAX — respond with JSON (for SweetAlert)
    if ($this->request->isAJAX()) {
      return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Package deleted successfully!'
      ]);
    }

    // ✅ If normal form submission — redirect back
    return redirect()
      ->to('/packages/' . $whId)
      ->with('success', 'Package deleted successfully!');
  }


  public function addItem($packageId)
  {
    $itemModel = new PackageItemModel();

    $rules = [
      'quantity' => 'required|integer',
      'value' => 'permit_empty|decimal',
      'weight' => 'permit_empty|decimal',
      'description' => 'permit_empty|string',
      'hs_code' => 'permit_empty|string',
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()
        ->with('error', 'Please fix the errors below.')
        ->with('validation', $this->validator);
    }

    $data = [
      'package_id' => $packageId,
      'quantity'   => $this->request->getPost('quantity'),
      'value'      => $this->request->getPost('value'),
      'hs_code'      => $this->request->getPost('hs_code'),
      'weight'     => $this->request->getPost('weight'),
      'description' => $this->request->getPost('description'),
    ];

    $itemModel->insert($data);

    return redirect()->to(base_url("packages/show/$packageId"))
      ->with('success', 'Package item added successfully.');
  }

  public function updateItem($id)
  {
    $itemModel = new PackageItemModel();
    $item = $itemModel->find($id);

    if (!$item) {
      return redirect()->back()->with('error', 'Item not found.');
    }

    $rules = [
      'name' => 'required|min_length[2]',
      'quantity' => 'required|integer',
      'value' => 'permit_empty|decimal',
      'weight' => 'permit_empty|decimal',
      'description' => 'permit_empty|string',
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()
        ->with('error', 'Please correct the errors below.')
        ->with('validation', $this->validator);
    }

    $data = [
      'name'       => $this->request->getPost('name'),
      'quantity'   => $this->request->getPost('quantity'),
      'value'      => $this->request->getPost('value'),
      'weight'     => $this->request->getPost('weight'),
      'description' => $this->request->getPost('description'),
    ];

    $itemModel->update($id, $data);

    return redirect()->to(base_url("packages/show/" . $item['package_id']))
      ->with('success', 'Package item updated successfully.');
  }

  public function deleteItem($id)
  {
    $itemModel = new PackageItemModel();
    $item = $itemModel->find($id);

    if (!$item) {
      return redirect()->back()->with('error', 'Item not found.');
    }

    $itemModel->delete($id);

    return redirect()->to(base_url("packages/show/" . $item['package_id']))
      ->with('success', 'Package item deleted successfully.');
  }

  public function uploadFile($packageId)
  {
    // Get the uploaded file and form data
    $file = $this->request->getFile('file');
    $fileType = $this->request->getPost('file_type'); // "invoice", "photo", "label", "other"

    // Validate file
    if (!$file->isValid()) {
      return redirect()->back()->with('error', 'Invalid file.');
    }

    // Generate random file name and move it to uploads directory
    $newName = $file->getRandomName();
    $file->move(FCPATH . 'uploads/packages/', $newName);

    // Save file details in the database
    $fileModel = new PackageFileModel();
    $fileModel->insert([
      'package_id'   => $packageId,
      'file_path'    => 'uploads/packages/' . $newName,
      'file_name'    => $file->getClientName(),
      'file_type'    => $fileType,
      'uploaded_by'  => session()->get('user_id'),
      'created_at'   => date('Y-m-d H:i:s'),
    ]);

    /**
     * ✅ If the uploaded file type is "label", send an email to the customer.
     */
    if ($fileType === 'label') {
      $packageModel = new PackageModel();
      $package = $packageModel->where('id', $packageId)->first();

      $user_id = $package['user_id'];



      $model = new \App\Models\UserModel();
      $user = $model->where('id', $user_id)->first();

      if ($user) {

        $link['name'] = $user['firstname'] . ' ' . $user['lastname'];
        $user_email = $user['email'];

        $link['text'] = base_url("packages/show/$packageId/#files");


        $message = view('emails/download_label', $link);

        $email = \Config\Services::email();

        $email->setTo($user_email);
        $email->setSubject('Password Reset Link');
        $email->setMessage($message);
        $email->setMailType('html'); // Important

        $email->send();
      }

      // Redirect back to the package detail page
      return redirect()->to(base_url("packages/show/$packageId"))
        ->with('success', 'File uploaded successfully.');
    }
  }

  public function deleteFile($id)
  {

    $fileModel = new PackageFileModel();
    $file = $fileModel->find($id);

    if (!$file) {
      return redirect()->back()->with('error', 'File not found.');
    }

    if (file_exists(FCPATH . $file['file_path'])) {
      unlink(FCPATH . $file['file_path']);
    }

    $fileModel->delete($id);

    return redirect()->to(base_url("packages/show/" . $file['package_id']))
      ->with('success', 'File deleted successfully.');
  }

  public function getVirtualAddresses()
  {
    $session = session();
    $role = $session->get('role');

    // Optionally restrict based on role if needed
    // if ($role !== 'admin') return $this->response->setJSON([]);

    $virtualAddressModel = new \App\Models\VirtualAddressModel();

    $addresses = $virtualAddressModel->where('is_active', 1)->findAll();

    // Return JSON response
    return $this->response->setJSON($addresses);
  }



  public function getShippingData()
  {
    if (!$this->request->isAJAX()) {
      return redirect()->back();
    }

    $data = $this->request->getJSON(true); // decode JSON to associative array
    $packageIds = $data['package_ids'] ?? [];

    if (empty($packageIds)) {
      return $this->response->setJSON(['error' => 'No packages selected.']);
    }

    $packageModel = new PackageModel();
    $virtualAddressModel = new VirtualAddressModel();

    // Use the first package as reference
    $package = $packageModel->find($packageIds[0]);

    if (!$package) {
      return $this->response->setJSON(['error' => 'Package not found.']);
    }
    $packageModel = new PackageModel();
    $packages = $packageModel->whereIn('id', $packageIds)->findAll();

    $totalWeight = 0;
    $totalLength = 0;
    $totalWidth  = 0;
    $totalHeight = 0;

    foreach ($packages as $pkg) {
      $totalWeight += $pkg['weight'];
      $totalLength += $pkg['length'];
      $totalWidth  += $pkg['width'];
      $totalHeight += $pkg['height'];
    }



    $currentWarehouse = $virtualAddressModel->find($package['virtual_address_id']);
    $availableWarehouses = $virtualAddressModel->where('is_active', 1)->findAll();

    return $this->response->setJSON([
      'currentWarehouse' => $currentWarehouse,
      'availableWarehouses' => $availableWarehouses,
      'packageTotals' => [
        'weight' => $totalWeight,
        'length' => $totalLength,
        'width'  => $totalWidth,
        'height' => $totalHeight,
      ]
    ]);
  }



  // Calculate rates (existing method, slightly adapted for JSON POST)
  public function getRates()
  {
    if (!$this->request->isAJAX()) {
      return redirect()->back();
    }

    $data = $this->request->getJSON(true);

    try {
      $client = new \GuzzleHttp\Client();
      $payload = [
        "origin_address" => [
          "line_1" => $data['origin_line_1'] ?? '',
          "state" => $data['origin_state'] ?? '',
          "city" => $data['origin_city'] ?? '',
          "postal_code" => $data['origin_postal_code'] ?? '',
          "country_alpha2" => getCountryCode($data['origin_country']) ?? '',
        ],
        "destination_address" => [
          "line_1" => $data['dest_line_1'] ?? '',
          "state" => $data['dest_state'] ?? '',
          "city" => $data['dest_city'] ?? '',
          "postal_code" => $data['dest_postal_code'] ?? '',
          "country_alpha2" => getCountryCode($data['dest_country']) ?? '',
        ],
        "incoterms" => "DDU",
        "parcels" => [[
          "total_actual_weight" => (float)($data['weight'] ?? 1),
          "items" => [[
            "origin_country_alpha2" => getCountryCode($data['origin_country']) ?? '',
            "quantity" => 1,
            "declared_currency" => "USD",
            "description" => $data['category'] ?? 'Package Item',
            "category" => $data['category'] ?? 'General',
            "hs_code" => $data['hs_code'] ?? '',
            "actual_weight" => (float)($data['weight'] ?? 1),
            "dimensions" => [
              "length" => (float)($data['length'] ?? 10),
              "width"  => (float)($data['width'] ?? 10),
              "height" => (float)($data['height'] ?? 10),
            ],
            "declared_customs_value" => 20
          ]]
        ]]
      ];

      $response = $client->post('https://public-api.easyship.com/2024-09/rates', [
        'headers' => [
          'Authorization' => 'Bearer ' . trim($this->token),
          'Content-Type' => 'application/json',
          'Accept' => 'application/json; version=2024-09',
        ],
        'json' => $payload,
        'http_errors' => false,
      ]);

      $body = json_decode($response->getBody(), true);
      return $this->response->setJSON(['data' => $body, 'payload' => $payload]);
    } catch (\Exception $e) {
      return $this->response->setJSON(['error' => $e->getMessage(), 'payload' => $payload]);
    }
  }
  public function getShippingCategories()
  {
    $client = new \GuzzleHttp\Client();

    try {
      $response = $client->request('GET', 'https://public-api.easyship.com/2024-09/item_categories', [
        'headers' => [
          'Authorization' => 'Bearer ' . trim($this->token),
          'Accept'        => 'application/json; version=2024-09',
        ],
        'timeout' => 10,
      ]);

      $data = json_decode($response->getBody(), true);
      $categories = $data['item_categories'] ?? [];

      return $this->response->setJSON([
        'success' => true,
        'categories' => $categories
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'error' => 'Unable to fetch categories at this time.'
      ]);
    }
  }
}
