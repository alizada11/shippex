<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
 protected $table      = 'admin_notifications';  // Table name
 protected $primaryKey = 'id';                    // Primary key of the table
 protected $allowedFields = ['title', 'action', 'model', 'record_id', 'user_name', 'user_email', 'link', 'is_read', 'created_at'];
 public $useTimestamps = false;
}
