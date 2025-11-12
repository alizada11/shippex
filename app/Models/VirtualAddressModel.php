<?php

namespace App\Models;

use CodeIgniter\Model;

class VirtualAddressModel extends Model
{
 protected $table = 'virtual_addresses';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'country',
  'address_line',
  'address_line_1',
  'address_line_2',
  'postal_code',
  'phone',
  'city',
  'code',
  'is_active',
  'map_link'
 ];
 protected $useTimestamps = true;
}
