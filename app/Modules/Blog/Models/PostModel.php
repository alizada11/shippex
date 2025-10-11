<?php

namespace Modules\Blog\Models;


use CodeIgniter\Model;
use Modules\Blog\Entities\Post;


class PostModel extends Model
{
  protected $table = 'posts';
  protected $returnType = Post::class;
  protected $useTimestamps = true;
  protected $useSoftDeletes = true;


  protected $allowedFields = [
    'title',
    'slug',
    'excerpt',
    'content',
    'status',
    'author_id',
    'thumbnail',
    'category_id',
    'published_at'
  ];

  protected $datamap = [
    'category_name' => 'category_name',
    'category_slug' => 'category_slug'
  ];
  public function getPostsWithCategory()

  {
    return $this->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug')
      ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left');
  }

  protected $validationRules = [
    'title' => 'required|min_length[3]',
    'slug' => 'required|alpha_dash',
    'status' => 'in_list[draft,published]'
  ];


  public function published()
  {
    return $this->where('status', 'published');
  }
}
