<?php

namespace Modules\Blog\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
 protected $table = 'blog_categories';
 protected $primaryKey = 'id';
 protected $allowedFields = ['name', 'slug'];
 protected $useTimestamps = true;

 public function getPostsWithCategory()

 {
  return $this->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug')
   ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left');
 }
}
