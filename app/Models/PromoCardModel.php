<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoCardModel extends Model
{
 protected $table = 'promo_cards';
 protected $primaryKey = 'id';
 protected $allowedFields = ['title', 'description', 'button_text', 'button_url', 'image', 'background'];
 protected $useTimestamps = true;
}
