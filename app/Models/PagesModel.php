<?php
// app/Models/PagesModel.php
namespace App\Models;

use CodeIgniter\Model;

class PagesModel extends Model
{
 protected $table = 'pages';
 protected $primaryKey = 'id';
 protected $allowedFields = ['title', 'content'];
 protected $returnType = 'array';
 public $useTimestamps = true; // optional
}
