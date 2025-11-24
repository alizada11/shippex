<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
 protected $table = 'users';
 protected $primaryKey = 'id';
 protected $allowedFields = [
  'firstname',
  'lastname',
  'username',
  'email',
  'phone_number',
  'password',
  'role',
  'email_verified',
  'email_verification_token',
  'email_verified_at',
  'created_at',
  'remember_token'
 ];
}
