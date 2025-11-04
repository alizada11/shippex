<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageFileModel extends Model
{
 protected $table = 'package_files';
 protected $primaryKey = 'id';
 protected $allowedFields = ['package_id', 'file_type', 'file_path', 'uploaded_at'];
 public $useTimestamps = false;

 public function getFilesByPackage($packageId)
 {
  return $this->where('package_id', $packageId)->findAll();
 }
}
