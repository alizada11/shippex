<?php

namespace App\Models;

use CodeIgniter\Model;

class HowItWorksStepModel extends Model
{
 protected $table      = 'how_it_works_steps';
 protected $primaryKey = 'id';
 protected $allowedFields = ['section_id', 'title', 'description', 'image', 'bg_image', 'order'];
 protected $useTimestamps = true;
}
