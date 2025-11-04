<?php

namespace App\Models;

use CodeIgniter\Model;

class CombineRepackRequestModel extends Model
{
 protected $table      = 'combine_repack_requests';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'user_id',
  'package_ids',
  'warehouse_id',
  'total_weight',
  'total_length',
  'total_width',
  'total_height',
  'status',
  'admin_id',
  'requested_at',
  'processed_at'
 ];
 protected $useTimestamps = true;

 // Helper: decode JSON package_ids
 public function getPackageIds($request)
 {
  return json_decode($request['package_ids'], true);
 }
}
