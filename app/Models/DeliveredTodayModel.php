<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveredTodayModel extends Model
{
 protected $table      = 'delivered_today';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'courier_logo',
  'retailer_logo',
  'icon',
  'from_country',
  'from_flag',
  'to_country',
  'to_flag',
  'cost',
  'weight'
 ];
 protected $useTimestamps = true;
}
