<?php

namespace App\Models;

use CodeIgniter\Model;

class ShippingServiceModel extends Model
{
 protected $table = 'shipping_services';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'provider_name',
  'service_name',
  'provider_logo',
  'price',
  'currency',
  'rating',
  'transit_text',
  'transit_days',
  'features',
  'quote_key',
  'request_id'
 ];

 protected $useTimestamps = true;
 protected $createdField  = 'created_at';
 protected $updatedField  = 'updated_at';

 // When retrieving, decode JSON features to array
 protected $afterFind = ['decodeFeatures'];
 protected $afterInsert = [];
 protected $afterUpdate = [];

 protected function decodeFeatures(array $data)
 {
  if (!empty($data['data'])) {
   foreach ($data['data'] as &$row) {
    if (isset($row['features']) && $row['features'] !== null) {
     $decoded = json_decode($row['features'], true);
     $row['features'] = $decoded === null ? $row['features'] : $decoded;
    }
   }
   $data['data'] = $data['data'];
  }
  return $data;
 }
}
