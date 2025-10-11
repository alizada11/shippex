<?php

namespace App\Models;

use CodeIgniter\Model;

class WhyChooseModel extends Model
{
 protected $table      = 'why_choose';
 protected $primaryKey = 'id';
 protected $allowedFields = ['title', 'description', 'icon', 'order'];
 protected $useTimestamps = true;
}
