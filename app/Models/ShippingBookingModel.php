<?php

namespace App\Models;

use CodeIgniter\Model;

class ShippingBookingModel extends Model
{
 protected $table      = 'shipping_bookings';
 protected $primaryKey = 'id';

 protected $allowedFields = [
  'origin_line_1',
  'origin_city',
  'origin_state',
  'origin_postal',
  'origin_country',
  'dest_line_1',
  'dest_city',
  'dest_state',
  'dest_postal',
  'dest_country',
  'weight',
  'length',
  'width',
  'height',
  'courier_name',
  'service_name',
  'delivery_time',
  'currency',
  'total_charge',
  'description',
  'status',
  'category',
  'user_id',
  'purchase_invoice',
  'payment_status',
  'payment_info'
 ];

 protected $useTimestamps = true;
}
