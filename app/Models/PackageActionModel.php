<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageActionModel extends Model
{
 protected $table = 'package_actions';
 protected $primaryKey = 'id';
 protected $allowedFields = ['package_id', 'action', 'notes', 'performed_by', 'created_at'];
 public $useTimestamps = false;

 public function logAction($packageId, $action, $notes = null, $user = null)
 {
  return $this->insert([
   'package_id' => $packageId,
   'action' => $action,
   'notes' => $notes,
   'performed_by' => $user,
   'created_at' => date('Y-m-d H:i:s'),
  ]);
 }

 public function getHistory($packageId)
 {
  return $this->where('package_id', $packageId)->orderBy('created_at', 'DESC')->findAll();
 }
}
