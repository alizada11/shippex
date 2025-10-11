<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqModel extends Model
{
 protected $table = 'faqs';
 protected $primaryKey = 'id';
 protected $allowedFields = ['question', 'answer'];
 protected $useTimestamps = true;
}
