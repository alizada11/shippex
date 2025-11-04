<?php



$session = session();
$lang = $session->get('lang');

use App\Models\UserModel;
use App\Models\VirtualAddressModel;







if (!function_exists('warehousesMenu')) {
  function warehousesMenu(): string
  {
    $addressesModel = new \App\Models\VirtualAddressModel();
    $addresses = $addressesModel->findAll();

    $html = '';

    foreach ($addresses as $adr) {
      $url = base_url('warehouse/' . $adr['code']); // no leading slash
      $html .= '<li><a class="dropdown-item py-2" href="' . $url . '">'
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
      $url = base_url('packages/' . $adr['id']); // no leading slash

      // check if this submenu is active
      $isActive = strpos($currentUrl, '/packages/' . $adr['id']) !== false;

      $html .= '<li class="nav-item">
                        <a class="nav-link ' . ($isActive ? 'active' : '') . '" href="' . $url . '">
                            <i class="fi fi-' . $flagCode . '"></i> ' . esc($adr['country']) . '
                        </a>
                      </li>';
    }

    return $html;
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
    $fullname = $user['firstname'] . ' ' . $user['lastname'];
    return $fullname;
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
