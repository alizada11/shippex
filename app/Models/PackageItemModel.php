<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageItemModel extends Model
{
 protected $table = 'package_items';
 protected $primaryKey = 'id';
 protected $useTimestamps = true;

 protected $allowedFields = [
  'package_id',
  'description',
  'hs_code',
  'quantity',
  'value'
 ];

 public function getByPackage($packageId)
 {
  return $this->where('package_id', $packageId)->findAll();
 }
}
