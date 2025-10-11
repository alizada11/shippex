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
      $url = base_url('/warehouses/' . $adr['code']);
      $html .= '<li><a class="dropdown-item" href="' . $url . '">'
        . esc($adr['code']) . ' Warehouses</a></li>';
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
