<?php

namespace App\Models;

use CodeIgniter\Model;

class ShopperItemModel extends Model
{
 protected $table = 'shopper_items';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'request_id',
  'name',
  'url',
  'size',
  'color',
  'instructions',
  'quantity'
 ];
}
