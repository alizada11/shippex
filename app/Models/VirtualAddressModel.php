<?php

namespace App\Models;

use CodeIgniter\Model;

class VirtualAddressModel extends Model
{
 protected $table = 'virtual_addresses';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'user_id',
  'country',
  'address_line',
  'postal_code',
  'phone',
  'city',
  'code',
  'is_default'
 ];
 protected $useTimestamps = true;
}
