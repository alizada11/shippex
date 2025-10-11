<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingStatusHistoryModel extends Model
{
 protected $table = 'booking_status_history';
 protected $primaryKey = 'id';
 protected $allowedFields = ['book_id', 'old_status', 'new_status', 'changed_by', 'changed_at'];
 protected $useTimestamps = true;
 protected $createdField  = 'changed_at';
 protected $updatedField  = ''; // not needed
}
