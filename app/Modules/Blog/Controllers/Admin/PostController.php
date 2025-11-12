<?php

namespace Modules\Blog\Controllers\Admin;


use App\Controllers\BaseController;
use Modules\Blog\Models\CategoryModel;
use Modules\Blog\Models\PostModel;


class PostController extends BaseController
{
  public function index()
  {
    $model = new PostModel();
    $data['posts'] = $model->orderBy('created_at', 'DESC')->paginate(20);
    $data['pager'] = $model->pager;
    $data['title'] = 'Blog Posts';
    return view('Modules\\Blog\\Views\\admin\\posts\\index', $data);
  }
  public function published()
  {
    $model = new PostModel();
    $data['posts'] = $model->where('status', 'published')->orderBy('created_at', 'DESC')->paginate(20);
    $data['pager'] = $model->pager;
    $data['title'] = 'Published Posts';
    return view('Modules\\Blog\\Views\\admin\\posts\\index', $data);
  }
  public function draft()
  {
    $model = new PostModel();
    $data['posts'] = $model->where('status', 'draft')->orderBy('created_at', 'DESC')->paginate(20);
    $data['pager'] = $model->pager;
    $data['title'] = 'Draft Posts';
    return view('Modules\\Blog\\Views\\admin\\posts\\index', $data);
  }


  public function create()
  {
    $categories = (new CategoryModel())->findAll();
    return view('Modules\\Blog\\Views\\admin\\posts\\form', ['post' => null, 'title' => 'Create Post', 'categories' => $categories]);
  }


  protected function makeSlug(string $title): string
  {
    helper('text');
    $slug = url_title(convert_accented_characters($title), '-', true);
    $model = new PostModel();
    $base = $slug;
    $i = 1;
    while ($model->where('slug', $slug)->withDeleted()->first()) {
      $slug = $base . '-' . $i++;
    }
    return $slug;
  }


  public function store()
  {
    $data = $this->request->getPost();


    if (empty($data['slug'])) $data['slug'] = $this->makeSlug($data['title'] ?? '');
    if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
      $data['published_at'] = date('Y-m-d H:i:s');
    }
    $file = $this->request->getFile('thumbnail');
    if ($file && $file->isValid() && !$file->hasMoved()) {
      $newName = $file->getRandomName();
      $file->move(FCPATH . 'uploads/blog', $newName);
      $data['thumbnail'] = $newName;

      // Optional: Delete old thumbnail on update
      if (!empty($data->thumbnail) && file_exists(FCPATH . 'uploads/blog/' . $data->thumbnail)) {
        unlink(FCPATH . 'uploads/blog/' . $data->thumbnail);
      }
    }

    $data['author_id'] = session()->get('user_id');
    $model = new PostModel();
    if (!$model->save($data)) {
      return redirect()->back()->with('errors', $model->errors())->withInput();
    }
    return redirect()->to('/admin/blog/posts')->with('message', 'Post created');
  }


  public function edit(int $id)
  {
    $categories = (new CategoryModel())->findAll();
    $post = (new PostModel())->find($id);
    if (!$post) return redirect()->to('/admin/blog/posts');
    return view('Modules\\Blog\\Views\\admin\\posts\\form', ['post' => $post, 'title' => 'Edit Post', 'categories' => $categories]);
  }


  public function update(int $id)
  {

    // Use the module model (adjust namespace if yours differs)
    $postModel = new \Modules\Blog\Models\PostModel();

    // Fetch existing post (entity)
    $post = $postModel->find($id);
    if (! $post) {
      return redirect()->back()->with('error', 'Post not found.');
    }

    // Get POST data
    $input = $this->request->getPost();

    // Normalize fields
    $title   = trim($input['title'] ?? '');
    $slug    = trim($input['slug'] ?? '');
    $content = $input['content'] ?? '';
    $status  = $input['status'] ?? 'draft';
    $categoryId = $input['category_id'] ?? null;

    // Auto-generate slug if empty
    if ($slug === '') {
      helper('text');
      $slug = url_title(convert_accented_characters($title), '-', true);
    }

    // --- Basic validation (server-side) ---
    $errors = [];

    if (strlen($title) < 3) {
      $errors['title'] = 'Title must be at least 3 characters.';
    }

    if ($content === '') {
      $errors['content'] = 'Content is required.';
    }

    // Check slug uniqueness only if slug changed
    if ($slug !== $post->slug) {
      $exists = $postModel->where('slug', $slug)
        ->where('id !=', $id)
        ->first();

      if ($exists) {


        $errors['slug'] = 'This slug is already in use.';
      }
    }

    if (! empty($errors)) {
      return redirect()->back()->withInput()->with('errors', $errors);
    }

    // Build update payload
    $updateData = [
      'title'   => $title,
      'slug'    => $slug,
      'content' => $content,
      'status'  => $status,
    ];

    if ($categoryId !== null) {
      $updateData['category_id'] = $categoryId;
    }

    // --- Thumbnail upload (only if a file is provided) ---
    $file = $this->request->getFile('thumbnail');
    if ($file && $file->isValid() && ! $file->hasMoved()) {
      // Optional: validate mime/size here using $this->validate() rules
      $newName = $file->getRandomName();
      $file->move(FCPATH . 'uploads/blog', $newName);

      // Set new thumbnail to be saved
      $updateData['thumbnail'] = $newName;

      // Delete old thumbnail file if it exists and is different
      if (! empty($post->thumbnail) && file_exists(FCPATH . 'uploads/blog/' . $post->thumbnail)) {
        @unlink(FCPATH . 'uploads/blog/' . $post->thumbnail);
      }
    }
    // IMPORTANT: do NOT set thumbnail = null when no file uploaded — leave it unchanged

    // Perform update
    if (! $postModel->update($id, $updateData)) {
      // If model validation fails, $postModel->errors() may have details
      $modelErrors = $postModel->errors();
      return redirect()->back()->withInput()->with('errors', $modelErrors ?: ['update' => 'Unable to update post.']);
    }



    return redirect()->to('/admin/blog/posts')->with('success', 'Post updated successfully.');
  }
  public function destroy($id)
  {
    $userId = session()->get('user_id');
    $model = new PostModel();
    $address = $model->where('id', $id)->first();

    if (!$address) {
      return redirect()->to('/addresses')->with('error', 'Address not found.');
    }



    $model->delete($id);
    return redirect()->to('/admin/blog/posts')->with('success', 'Post deleted successfully.');
  }

  public function uploadImage()
  {
    $file = $this->request->getFile('upload');
    if (!$file || !$file->isValid()) return $this->response->setJSON(['error' => 'No file uploaded']);

    $newName = $file->getRandomName();
    $file->move(FCPATH . 'uploads/blog', $newName);

    return $this->response->setJSON(['url' => base_url('uploads/blog/' . $newName)]);
  }
}
