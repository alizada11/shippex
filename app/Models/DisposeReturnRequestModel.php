<?php

namespace App\Models;

use CodeIgniter\Model;

class DisposeReturnRequestModel extends Model
{
 protected $table = 'dispose_return_requests';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'user_id',
  'package_id',
  'request_type',
  'reason',
  'status',
  'admin_id'
 ];
 protected $useTimestamps = true;
}
