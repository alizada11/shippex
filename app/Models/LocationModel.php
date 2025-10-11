<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
 protected $table      = 'locations';
 protected $primaryKey = 'id';

 protected $allowedFields = [
  'name',
  'flag_image',
  'thumbnail_image',
  'location_info',
  'link',
  'status'
 ];

 protected $useTimestamps = true;
}
