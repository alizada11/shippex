<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 my-4">
 <!-- Header Section -->
 <div class="d-flex justify-content-between align-items-center mb-4">
  <div>
   <h1 class="h3 mb-1 text-gray-800">Blog Management</h1>
   <p class="text-muted">Create and manage blog content for your shipping insights</p>
  </div>
  <a href="/admin/blog/posts/create" class="btn btn-primary d-flex align-items-center">
   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle me-2" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
   </svg>
   New Post
  </a>
 </div>

 <!-- Stats Cards -->
 <div class="row mb-4">
  <div class="col-xl-3 col-md-6 mb-4">
   <div class="card border-left-primary shadow-sm h-100 py-2">
    <div class="card-body">
     <div class="row no-gutters align-items-center">
      <div class="col mr-2">
       <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Posts</div>
       <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($posts) ?></div>
      </div>
      <div class="col-auto">
       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#4e73df" class="bi bi-journals" viewBox="0 0 16 16">
        <path d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2 2 2 0 0 1-2 2H3a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1H1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2z" />
        <path d="M1 6v-.5a.5.5 0 0 1 1 0V6h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z" />
       </svg>
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
   <div class="card border-left-success shadow-sm h-100 py-2">
    <div class="card-body">
     <div class="row no-gutters align-items-center">
      <div class="col mr-2">
       <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Published</div>
       <div class="h5 mb-0 font-weight-bold text-gray-800">
        <?= count(array_filter($posts, function ($p) {
         return $p->status === 'published';
        })) ?>
       </div>
      </div>
      <div class="col-auto">
       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#1cc88a" class="bi bi-check-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
       </svg>
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
   <div class="card border-left-warning shadow-sm h-100 py-2">
    <div class="card-body">
     <div class="row no-gutters align-items-center">
      <div class="col mr-2">
       <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Draft Posts</div>
       <div class="h5 mb-0 font-weight-bold text-gray-800">
        <?= count(array_filter($posts, function ($p) {
         return $p->status === 'draft';
        })) ?>
       </div>
      </div>
      <div class="col-auto">
       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#f6c23e" class="bi bi-pencil-square" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
       </svg>
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-4">
   <div class="card border-left-info shadow-sm h-100 py-2">
    <div class="card-body">
     <div class="row no-gutters align-items-center">
      <div class="col mr-2">
       <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Latest Post</div>
       <div class="h5 mb-0 font-weight-bold text-gray-800">
        <?= !empty($posts) ? date('M j', strtotime($posts[0]->created_at)) : 'N/A' ?>
       </div>
      </div>
      <div class="col-auto">
       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#36b9cc" class="bi bi-clock-history" viewBox="0 0 16 16">
        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
        <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
       </svg>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>

 <!-- Posts Table -->
 <div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center">
   <h6 class="m-0 font-weight-bold text-white">All Blog Posts</h6>
   <div class="dropdown">
    <button class="btn btn-sm text-white btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter me-1" viewBox="0 0 16 16">
      <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
     </svg>
     Filter
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
     <li><a class="dropdown-item" href="<?= base_url('filter/posts/all') ?>">All Posts</a></li>
     <li><a class="dropdown-item" href="<?= base_url('filter/posts/published') ?>">Published</a></li>
     <li><a class="dropdown-item" href="<?= base_url('filter/posts/draft') ?>">Drafts</a></li>
    </ul>
   </div>
  </div>
  <div class="card-body p-0">
   <div class="table-responsive">
    <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
     <thead class="table-light">
      <tr>
       <th>Title</th>
       <th>Status</th>
       <th>Published</th>
       <th class="text-center">Actions</th>
      </tr>
     </thead>
     <tbody>
      <?php foreach ($posts as $p): ?>
       <tr>
        <td class="font-weight-bold"><?= esc($p->title) ?></td>
        <td>
         <?php if ($p->status === 'published'): ?>
          <span class="badge badge-success bg-success">Published</span>
         <?php else: ?>
          <span class="badge badge-warning bg-warning">Draft</span>
         <?php endif; ?>
        </td>
        <td><?= date('M j, Y', strtotime($p->created_at)) ?></td>
        <td class="actions-col">
         <div class="action-buttons-table">
          <a href="<?= site_url('/admin/blog/posts/' . $p->id . '/edit') ?>" class="btn btn-action edit">
           <i class="fas fa-edit "></i>
          </a>

          <form action="<?= site_url('/admin/blog/posts/' . $p->id . '/delete') ?>" class="delete-form" method="post">
           <?= csrf_field() ?>
           <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></button>
          </form>
         </div>
        </td>

       </tr>
      <?php endforeach; ?>
     </tbody>
    </table>
   </div>

   <?php if (empty($posts)): ?>
    <div class="text-center py-4">
     <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ddd" class="bi bi-journal-text mb-3" viewBox="0 0 16 16">
      <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z" />
      <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z" />
      <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z" />
     </svg>
     <h5 class="text-muted">No blog posts yet</h5>
     <p class="text-muted">Get started by creating your first blog post</p>
     <a href="/admin/blog/posts/create" class="btn btn-primary mt-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
       <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
       <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
      </svg>
      Create Your First Post
     </a>
    </div>
   <?php endif; ?>
  </div>
 </div>
</div>

<style>
 .card {
  border: none;
  border-radius: 0.5rem;
 }

 .btn {
  border-radius: 0.35rem;
  font-weight: 500;
 }

 .table th {
  border-top: none;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 0.5px;
  color: #6e707e;
 }

 .badge {
  font-size: 0.75em;
  padding: 0.35em 0.65em;
 }

 .border-left-primary {
  border-left: 4px solid #4e73df !important;
 }

 .border-left-success {
  border-left: 4px solid #1cc88a !important;
 }

 .border-left-warning {
  border-left: 4px solid #f6c23e !important;
 }

 .border-left-info {
  border-left: 4px solid #36b9cc !important;
 }

 .card-shadow {
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
 }
</style>

<?= $this->endSection() ?>