<?php

namespace App\Models;

use CodeIgniter\Model;

class ShopperRequestModel extends Model
{
 protected $table = 'shopper_requests';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'user_id',
  'status',
  'use_another_retailer',
  'delivery_description',
  'delivery_notes',
  'is_saved',
  'payment_status',
  'payment_info',
  'price',
  'updated_at'
 ];
 protected $useTimestamps = true;
 protected $createdField = 'created_at';
 protected $updatedField = 'updated_at';
}
