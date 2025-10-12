<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehouseModel extends Model
{
 protected $table = 'warehouses';
 protected $primaryKey = 'id';

 protected $allowedFields = [
  'country_code',
  'country_name',
  'banner_image',
  'hero_title',
  'hero_image',
  'hero_description_1',
  'hero_description_2',
  'hero_cta_text',
  'hero_cta_link',
  'brands_title',
  'brands_text',
  'brands_image',
  'shipping_text',
  'payment_text',
  'bottom_title',
  'bottom_paragraph_1',
  'bottom_paragraph_2',
  'bottom_cta_text',
  'bottom_cta_link',
  'is_active'
 ];

 protected $useTimestamps = true;
}
