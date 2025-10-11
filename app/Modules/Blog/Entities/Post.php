<?php

namespace Modules\Blog\Entities;


use CodeIgniter\Entity\Entity;


class Post extends Entity
{
 protected $dates = ['created_at', 'updated_at', 'deleted_at', 'published_at'];


 public function getUrl(): string
 {
  return site_url('blog/' . $this->attributes['slug']);
 }
}
