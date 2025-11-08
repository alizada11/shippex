<?php

namespace Modules\Blog\Controllers;


use App\Controllers\BaseController;
use Modules\Blog\Models\CategoryModel;
use Modules\Blog\Models\PostModel;


class BlogController extends BaseController
{
 public function index()
 {
  $model = new PostModel();
  $data['posts'] = $model->published()->orderBy('created_at', 'DESC')->paginate(10);
  $data['title'] = 'Blog';
  $data['pager'] = $model->pager;
  return view('Modules\Blog\Views\public\index', $data);
 }



 public function show(string $slug)
 {
  $categories = (new CategoryModel())->findAll();
  $post = (new PostModel())->where('slug', $slug)->first();
  if (!$post || $post->status !== 'published') {
   throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  }
  $model = new PostModel();
  $latest = $model->published()
   ->orderBy('created_at', 'DESC')
   ->findAll(6); // fetch 6 latest published posts
  return view('Modules\\Blog\\Views\\public\\show', ['post' => $post, 'latest' => $latest, 'categories' => $categories]);
 }
 public function category(int $id)
 {
  $model = new PostModel();
  $data['posts'] = $model->where('category_id', $id)->orderBy('created_at', 'DESC')->paginate(10);

  $data['pager'] = $model->pager;
  return view('Modules\Blog\Views\public\index', $data);
 }

 public function blogs()
 {
  return view('blogs');
 }
}
