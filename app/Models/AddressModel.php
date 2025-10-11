<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
  protected $table      = 'addresses';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'user_id',
    'type',
    'first_name',
    'last_name',
    'street_address1',
    'street_address2',
    'city',
    'state',
    'zip_code',
    'country',
    'tax_id',
    'phone_primary',
    'phone_secondary',
    'show_shipping_price',
    'is_default'
  ];
  protected $useTimestamps = true; // manages created_at, updated_at automatically

  protected $validationRules = [
    'type'          => 'required|in_list[billing,shipping]',
    'first_name'    => 'required|max_length[50]',
    'last_name'     => 'required|max_length[50]',
    'street_address1' => 'required|max_length[150]',
    'street_address2' => 'permit_empty|max_length[150]',
    'city'          => 'required|max_length[50]',
    'state'         => 'permit_empty|max_length[50]',
    'zip_code'      => 'permit_empty|max_length[20]',
    'country'       => 'required|max_length[50]',
    'tax_id'        => 'permit_empty|max_length[30]',
    'phone_primary' => 'required|max_length[25]',
    'phone_secondary' => 'permit_empty|max_length[25]',
    'show_shipping_price' => 'integer|in_list[0,1]',
    'is_default' => 'integer|in_list[0,1]',

  ];

  protected $validationMessages = [
    'type' => [
      'in_list' => 'Address type must be billing or shipping.'
    ]
    // add other custom messages as needed
  ];

  public function setDefault($userId, $type, $addressId)
  {
    log_message('debug', 'setDefault() - userId: ' . $userId . ', type: ' . $type . ', addressId: ' . $addressId);

    // Start a transaction to avoid race conditions
    $this->db->transStart();

    // 1. Clear existing default for this user & type
    $this->builder()
      ->where('user_id', $userId)
      ->where('type', $type)
      ->set(['is_default' => 0])
      ->update();

    // 2. Set the new default using update($id, $data)
    $result = $this->update($addressId, ['is_default' => 1]);

    // Complete transaction
    $this->db->transComplete();

    return $result;
  }
}
