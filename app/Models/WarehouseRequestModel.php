<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehouseRequestModel extends Model
{
 protected $table      = 'warehouse_requests';
 protected $primaryKey = 'id';

 protected $useTimestamps = true;
 protected $allowedFields = [
  'user_id',
  'warehouse_id',
  'status',
  'is_default',
  'rejectation_reason'
 ];

 // Optional: relations with user and warehouse
 public function getPendingRequests()
 {
  return $this->where('status', 'pending')->findAll();
 }
}
