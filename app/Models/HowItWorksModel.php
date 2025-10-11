<?php

namespace App\Models;

use CodeIgniter\Model;

class HowItWorksModel extends Model
{
 protected $table      = 'how_it_works';
 protected $primaryKey = 'id';

 protected $allowedFields = [
  'step_number',
  'subtitle',
  'title',
  'description',
  'icon'
 ];

 protected $useTimestamps = true;
}
