<?php

namespace App\Models;

use CodeIgniter\Model;

class HowItWorksSectionModel extends Model
{
 protected $table      = 'how_it_works_sections';
 protected $primaryKey = 'id';
 protected $allowedFields = ['title', 'description', 'image', 'button_text', 'button_link'];
 protected $useTimestamps = true;
}
