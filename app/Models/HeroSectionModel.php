<?php

namespace App\Models;

use CodeIgniter\Model;

class HeroSectionModel extends Model
{
 protected $table = 'hero_section';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'title',
  'subtitle',
  'description',
  'button_text',
  'button_link',
  'background_image'
 ];
}
