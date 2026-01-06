<?php



$session = session();
$lang = $session->get('lang');

use App\Models\UserModel;
use App\Models\VirtualAddressModel;
use CodeIgniter\Email\Email;
use App\Models\NotificationModel;
use Config\Services;
use CodeIgniter\Model;




if (!function_exists('warehousesMenu')) {
  function warehousesMenu(): string
  {
    $addressesModel = new \App\Models\VirtualAddressModel();
    $addresses = $addressesModel->findAll();

    $html = '';

    foreach ($addresses as $adr) {
      $url = base_url('warehouse/' . $adr['code']); // no leading slash
      $html .= '<li><a class="dropdown-item py-3" href="' . $url . '">'
        . esc($adr['code']) . ' Warehouse</a></li>';
    }

    return $html;
  }
}


if (!function_exists('getCountryCode')) {
  function getCountryCode($countryName)
  {
    $path = APPPATH . 'Views/partials/countries.json';
    if (!file_exists($path)) {
      return strtoupper(substr($countryName, 0, 2));
    }

    $countries = json_decode(file_get_contents($path), true);
    if (!$countries) {
      return strtoupper(substr($countryName, 0, 2));
    }

    foreach ($countries as $c) {
      if (strcasecmp($c['name'], $countryName) === 0) {
        if ($c['code'] == "UK") {
          $c['code'] = "GB";
        }
        return $c['code']; // e.g. 'JP'
      }
    }

    // fallback: first two letters
    return strtoupper(substr($countryName, 0, 2));
  }
}


if (!function_exists('adminWarehousesMenu')) {
  function adminWarehousesMenu(): string
  {
    $addressesModel = new \App\Models\VirtualAddressModel();
    $addresses = $addressesModel->findAll();

    $currentUrl = current_url(); // get current URL
    $html = '';

    foreach ($addresses as $adr) {
      $code = strtolower($adr['code'] ?? 'us');

      // Fix or map invalid country codes
      $map = [
        'uk' => 'gb', // show UK flag for 'uk'
        'gp' => 'gb', // treat 'gp' as UK
      ];

      $flagCode = $map[$code] ?? $code;
      $id = $adr['id'];
      $url = base_url('packages/' . $id);

      // Check if current URL matches any of the related routes
      $isActive =
        strpos($currentUrl, '/packages/' . $id) !== false ||
        strpos($currentUrl, '/packages/create/' . $id) !== false ||
        strpos($currentUrl, '/packages/' . $id . '/edit') !== false;

      $html .= '
        <li class="nav-item">
            <a class="nav-link ' . ($isActive ? 'active' : '') . '" href="' . $url . '">
                <i class="fi fi-' . $flagCode . '"></i> ' . esc($adr['country']) . '
            </a>
        </li>';
    }


    return $html;
  }
}


if (! function_exists('get_paypal_access_token')) {
  function get_paypal_access_token()
  {
    $cacheKey = 'paypal_access_token';
    $cached   = cache()->get($cacheKey);

    if ($cached && isset($cached['token']) && isset($cached['expires_at']) && $cached['expires_at'] > time()) {
      return $cached['token'];
    }

    $mode   = env('paypal.mode', 'sandbox');
    $client = env("paypal.{$mode}.client_id");
    $secret = env("paypal.{$mode}.client_secret");

    if (! $client || ! $secret) {
      log_message('error', 'PayPal credentials missing in .env');
      return null;
    }

    $url = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

    $ch = curl_init("{$url}/v1/oauth2/token");
    curl_setopt_array($ch, [
      CURLOPT_USERPWD        => "{$client}:{$secret}",
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER     => [
        'Accept: application/json',
        'Accept-Language: en_US',
      ],
    ]);

    $response    = curl_exec($ch);
    $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError   = curl_error($ch);
    curl_close($ch);

    if ($curlError || $httpCode !== 200) {
      log_message('error', 'PayPal token request failed: ' . $curlError . ' | HTTP: ' . $httpCode);
      return null;
    }

    $data = json_decode($response, true);

    if (isset($data['access_token']) && isset($data['expires_in'])) {
      // Cache 60 seconds early to avoid edge-case expiration
      $expiresAt = time() + $data['expires_in'] - 60;

      cache()->save($cacheKey, [
        'token'      => $data['access_token'],
        'expires_at' => $expiresAt,
      ], $data['expires_in'] - 60);

      return $data['access_token'];
    }

    log_message('error', 'Invalid PayPal token response: ' . $response);
    return null;
  }
}

if (! function_exists('paypal_api_request')) {
  function paypal_api_request(string $endpoint = '', string $method = 'POST', array $body = [])
  {
    $mode  = env('paypal.mode', 'sandbox');
    $base  = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    $token = get_paypal_access_token();

    $ch = curl_init("{$base}/v2/checkout/orders{$endpoint}");
    curl_setopt_array($ch, [
      CURLOPT_CUSTOMREQUEST  => $method,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
        'PayPal-Request-Id: ' . uniqid(), // Idempotency
      ],
    ]);

    if (! empty($body)) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $httpCode, 'data' => json_decode($response, true)];
  }
}

if (! function_exists('send_admin_notification')) {

  /**
   * Send a general admin notification (DB + Email)
   */
  function send_admin_notification(
    string $actionDescription,
    ?string $title = null,
    ?string $modelName = null,
    ?int $recordId = null,
    ?string $userName = null,
    ?string $userEmail = null,
    ?string $adminEmail = null,
    ?string $actionLink = null
  ): ?int {

    // -----------------------------
    // 1. Resolve admin email
    // -----------------------------
    $adminEmail = $adminEmail ?? 'codewithja@gmail.com';

    // -----------------------------
    // 2. Insert notification to DB
    // -----------------------------
    $notificationModel = new NotificationModel();

    $notificationData = [
      'title'        => $title,
      'action'       => $actionDescription,
      'model'        => $modelName,
      'record_id'    => $recordId,
      'user_name'    => $userName,
      'user_email'   => $userEmail,
      'link'         => $actionLink,
      'is_read'      => 0,
      'created_at'   => date('Y-m-d H:i:s'),
    ];

    try {
      $notificationId = $notificationModel->insert($notificationData, true);
    } catch (\Throwable $e) {
      log_message('error', 'Notification DB insert failed: ' . $e->getMessage());
      return null;
    }

    // -----------------------------
    // 3. Send email (non-blocking)
    // -----------------------------
    try {
      $email = Services::email();

      $email->setFrom('info@shippex.online', 'Shippex System');
      $email->setTo('codewithja@gmail.com');
      $email->setSubject('New Notification: ' . $title);
      $email->setMailType('html');

      $email->setMessage(view('emails/general_notification', [
        'title' => $title,
        'actionDescription' => $actionDescription,
        'modelName'         => $modelName,
        'recordId'          => $recordId,
        'userName'          => $userName,
        'userEmail'         => $userEmail,
        'actionLink'        => $actionLink,
      ]));

      if (! $email->send()) {
        log_message('error', 'Admin notification email failed');
        log_message('error', $email->printDebugger(['headers']));
      }
    } catch (\Throwable $e) {
      log_message('error', 'Email exception: ' . $e->getMessage());
    }

    // -----------------------------
    // 4. Return notification ID
    // -----------------------------
    return $notificationId;
  }
}



if (!function_exists('statusBadge')) {
  function statusBadge(string $status): string
  {
    $map = [
      'pending'   => 'bg-warning text-dark',
      'wait_for_payment'   => 'bg-warning text-dark',
      'canceled'  => 'bg-danger',
      'accepted'  => 'bg-primary',
      'shipping'  => 'bg-info text-dark',
      'shipped'   => 'bg-secondary',
      'delivered' => 'bg-success',
      'saved' => 'bg-primary',
    ];

    $class = $map[strtolower($status)] ?? 'bg-light text-dark';

    // Replace underscores with spaces and capitalize words
    $label = ucwords(str_replace('_', ' ', $status));

    return '<span class="badge ' . esc($class) . '">' . esc($label) . '</span>';
  }
}



if (!function_exists("fullname")) {
  function fullname($userId = null)
  {
    $model = new \App\Models\UserModel();
    $user = $model->select(['firstname', 'lastname'])->where('id', $userId)->first();
    if (!$user) return '-';

    $fullname = esc($user['firstname'] . ' ' . $user['lastname']);

    // Return clickable name (with data attribute)
    return '<a href="javascript:void(0);" style="text-decoration:none;color:#a84dff;" class="user-info-link" data-user-id="' . $userId . '">' . $fullname . '</a>';
  }
}


if (!function_exists("warehouse_name")) {
  function warehouse_name($id = null)
  {
    $model = new \App\Models\VirtualAddressModel();
    $wh = $model->select(['city', 'country', 'address_line'])->where('id', $id)->first();

    return $wh;
  }
}

// search

if (!function_exists('global_search')) {

  /**
   * Global database search with metadata
   */
  function global_search(string $modelClass, string $query, int $limit = 50): array
  {
    $allowedModels = [
      \App\Models\UserModel::class => 'user',
      \App\Models\PackageModel::class => 'package',
      \App\Models\ShippingBookingModel::class => 'shipping',
      \App\Models\ShopperRequestModel::class => 'shopper_request',
      \App\Models\WarehouseRequestModel::class => 'warehouse_request',
      \App\Models\CombineRepackRequestModel::class => 'combine_request',
      \App\Models\DisposeReturnRequestModel::class => 'dispose_request'
    ];

    if (!isset($allowedModels[$modelClass])) {
      log_message('error', 'Global search blocked invalid model: ' . $modelClass);
      return [];
    }

    $modelType = $allowedModels[$modelClass];
    $model = new $modelClass();
    $db = \Config\Database::connect();
    $table = $model->getTable();

    // Get searchable columns (exclude sensitive fields)
    $excludeFields = ['password', 'remember_token', 'deleted_at', 'payment_info'];
    $fields = array_diff($db->getFieldNames($table), $excludeFields);

    if (empty($fields)) {
      return [];
    }

    // Build search query
    $builder = $db->table($table);
    $builder->groupStart();
    foreach ($fields as $field) {
      $builder->orLike($field, $query);
    }
    $builder->groupEnd();
    $builder->limit($limit);

    $results = $builder->get()->getResultArray();

    // Add metadata to each result
    foreach ($results as &$result) {
      $result['_search_type'] = $modelType;
      $result['_model_class'] = $modelClass;
    }

    return $results;
  }
}

if (!function_exists('get_search_type_icon')) {
  function get_search_type_icon($type)
  {
    $icons = [
      'user' => 'fas fa-user',
      'package' => 'fas fa-box',
      'shipping' => 'fas fa-shipping-fast',
      'shopper_request' => 'fas fa-shopping-cart',
      'warehouse_request' => 'fas fa-warehouse',
      'combine_request' => 'fas fa-boxes',
      'dispose_request' => 'fas fa-trash'
    ];
    return $icons[$type] ?? 'fas fa-search';
  }
}

if (!function_exists('get_search_type_color')) {
  function get_search_type_color($type)
  {
    $colors = [
      'user' => 'success',
      'package' => 'primary',
      'shipping' => 'info',
      'shopper_request' => 'warning',
      'warehouse_request' => 'secondary',
      'combine_request' => 'purple',
      'dispose_request' => 'danger'
    ];
    return $colors[$type] ?? 'dark';
  }
}

if (!function_exists('get_search_type_label')) {
  function get_search_type_label($type)
  {
    $labels = [
      'user' => 'User',
      'package' => 'Package',
      'shipping' => 'Shipping',
      'shopper_request' => 'Shopper Request',
      'warehouse_request' => 'Warehouse Request',
      'combine_request' => 'Combine Request',
      'dispose_request' => 'Dispose Request'
    ];
    return $labels[$type] ?? 'Item';
  }
}

if (!function_exists('get_search_result_title')) {
  function get_search_result_title($result)
  {
    $type = $result['_search_type'] ?? 'unknown';

    switch ($type) {
      case 'user':
        return trim(($result['firstname'] ?? '') . ' ' . ($result['lastname'] ?? '')) ?: $result['username'] ?? 'User #' . $result['id'];

      case 'package':
        $title = 'Package';
        if (isset($result['retailer'])) $title .= ' from ' . $result['retailer'];
        if (isset($result['tracking_number'])) $title .= ' (' . $result['tracking_number'] . ')';
        return $title;

      case 'shipping':
        $title = 'Shipping';
        if (isset($result['courier_name'])) $title .= ' via ' . $result['courier_name'];
        if (isset($result['dest_city'])) $title .= ' to ' . $result['dest_city'];
        return $title;

      case 'shopper_request':
        return 'Shopper Request #' . ($result['id'] ?? '');

      case 'warehouse_request':
        return 'Warehouse Request #' . ($result['id'] ?? '');

      case 'combine_request':
        return 'Combine Request #' . ($result['id'] ?? '');

      case 'dispose_request':
        return 'Dispose Request #' . ($result['id'] ?? '');

      default:
        return 'Item #' . ($result['id'] ?? '');
    }
  }
}

if (!function_exists('get_search_key_fields')) {
  function get_search_key_fields($result)
  {
    $type = $result['_search_type'] ?? 'unknown';
    $keyFields = [];

    switch ($type) {
      case 'user':
        $keyFields['Username'] = $result['username'] ?? '';
        $keyFields['Email'] = $result['email'] ?? '';
        $keyFields['Phone'] = $result['phone_number'] ?? '';
        $keyFields['Role'] = $result['role'] ?? '';
        break;

      case 'package':
        $keyFields['Status'] = $result['status'] ?? '';
        $keyFields['Tracking'] = $result['tracking_number'] ?? '';
        $keyFields['Weight'] = isset($result['weight']) ? $result['weight'] . 'kg' : '';
        $keyFields['User'] = $result['user_id'] ?? '';
        break;

      case 'shipping':
        $keyFields['Status'] = $result['status'] ?? '';
        $keyFields['Courier'] = $result['courier_name'] ?? '';
        $keyFields['Destination'] = $result['dest_city'] ?? '';
        $keyFields['Amount'] = isset($result['total_charge']) ? $result['currency'] . ' ' . $result['total_charge'] : '';
        break;

      case 'shopper_request':
        $keyFields['Status'] = $result['status'] ?? '';
        $keyFields['Price'] = isset($result['price']) ? '$' . $result['price'] : '';
        $keyFields['Warehouse'] = $result['warehouse_id'] ?? '';
        break;

      default:
        // Generic fields for other types
        $genericFields = ['status', 'user_id', 'created_at', 'updated_at'];
        foreach ($genericFields as $field) {
          if (isset($result[$field]) && $result[$field]) {
            $keyFields[ucfirst(str_replace('_', ' ', $field))] = $result[$field];
          }
        }
    }

    // Filter out empty values
    return array_filter($keyFields);
  }
}
