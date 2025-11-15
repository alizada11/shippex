<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
 protected $table = 'packages';
 protected $primaryKey = 'id';
 protected $useTimestamps = true;

 protected $allowedFields = [
  'user_id',
  'virtual_address_id',
  'retailer',
  'courier',
  'tracking_number',
  'status',
  'weight',
  'width',
  'height',
  'length',
  'value',
  'handling',
  'return_number',
  'processed_by',
  'received_at',
  'storage_days',
  'combined_from',
  'shipping_fee',
  'over_due_fee'
 ];

 protected $validationRules = [
  // 'tracking_number' => 'required|min_length[5]',
  'virtual_address_id' => 'required|integer',
 ];
}
