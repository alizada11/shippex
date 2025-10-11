<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5 post-page">
 <div class="row">
  <!-- Main Content Column -->
  <div class="col-lg-8">
   <!-- Breadcrumb Navigation -->
   <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
     <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
     <li class="breadcrumb-item"><a href="<?= site_url('blog') ?>">Blog</a></li>
     <li class="breadcrumb-item active" aria-current="page"><?= esc(mb_strimwidth($post->title, 0, 30, '...')) ?></li>
    </ol>
   </nav>

   <!-- Article Header -->
   <article>
    <header class="mb-4">
     <h1 class="fw-bold display-5 mb-3"><?= esc($post->title) ?></h1>

     <div class="d-flex align-items-center text-muted mb-3">
      <div class="d-flex align-items-center me-3">
       <i class="far fa-calendar me-2"></i>
       <span><?= esc(date('F j, Y', strtotime($post->published_at ?? $post->created_at))) ?></span>
      </div>
      <div class="d-flex align-items-center">
       <i class="far fa-clock me-2"></i>
       <?php

       use CodeIgniter\Database\BaseUtils;

       function calculateReadingTime($content)
       {
        $words = str_word_count(strip_tags($content)); // remove HTML tags
        return ceil($words / 200); // 200 words per minute
       }
       ?>
       <span><?= calculateReadingTime($post->content) ?> min read</span>
      </div>
     </div>

     <?php if (! empty($post->thumbnail)): ?>
      <div class="my-4">
       <img src="<?= base_url('uploads/blog/' . $post->thumbnail) ?>" class="img-fluid thumbnail rounded-3 shadow" alt="<?= esc($post->title) ?>">
       <?php if (!empty($post->thumbnail_caption)): ?>
        <div class="text-center mt-2 text-muted small"><?= esc($post->thumbnail_caption) ?></div>
       <?php endif; ?>
      </div>
     <?php endif; ?>
    </header>

    <!-- Article Content -->
    <div class="mt-4 post-content fs-5 lh-base">
     <?= $post->content ?> <!-- Render CKEditor HTML directly -->
    </div>

    <!-- Article Footer -->
    <footer class="mt-5 pt-4 border-top">
     <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex">
       <span class="text-muted me-3">Share:</span>
       <div class="d-flex">
        <a href="#" class="text-decoration-none text-dark me-2"><i class="fab fa-facebook fa-lg"></i></a>
        <a href="#" class="text-decoration-none text-dark me-2"><i class="fab fa-twitter fa-lg"></i></a>
        <a href="#" class="text-decoration-none text-dark me-2"><i class="fab fa-linkedin fa-lg"></i></a>
        <a href="#" class="text-decoration-none text-dark"><i class="fas fa-link fa-lg"></i></a>
       </div>
      </div>
      <a href="<?= site_url('blog') ?>" class="btn btn-outline-primary">
       <i class="fas fa-arrow-left me-2"></i>Back to Blog
      </a>
     </div>
    </footer>
   </article>
  </div>

  <!-- Sidebar Column -->
  <div class="col-lg-4">
   <div class="ps-lg-4">
    <!-- Latest Blogs Section -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
     <div class="card-header bg-shippex-purple text-white rounded-top-3 py-3">
      <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Latest Posts</h5>
     </div>
     <div class="card-body p-3">
      <?php foreach ($latest as $last): ?>
       <div class="card border-0 mb-3">
        <div class="row g-0">
         <?php if (!empty($last->thumbnail)): ?>
          <div class="col-4">
           <img src="<?= base_url('uploads/blog/' . $last->thumbnail) ?>" class="img-fluid rounded" alt="<?= esc($last->title) ?>" style="height: 80px; object-fit: cover;">
          </div>
          <div class="col-8">
           <div class="card-body py-0 ps-2">
            <a href="<?= site_url('blog/' . esc($last->slug)) ?>" class="text-decoration-none">
             <h6 class="card-title text-dark fw-bold"><?= esc(mb_strimwidth($last->title, 0, 50, '...')) ?></h6>
            </a>
            <p class="card-text"><small class="text-muted"><?= esc(date('M j, Y', strtotime($last->published_at ?? $last->created_at))) ?></small></p>
           </div>
          </div>
         <?php else: ?>
          <div class="col-12">
           <div class="card-body p-2">
            <a href="<?= site_url('blog/' . esc($last->slug)) ?>" class="text-decoration-none">
             <h6 class="card-title text-dark fw-bold"><?= esc(mb_strimwidth($last->title, 0, 70, '...')) ?></h6>
            </a>
            <p class="card-text"><small class="text-muted"><?= esc(date('M j, Y', strtotime($last->published_at ?? $last->created_at))) ?></small></p>
           </div>
          </div>
         <?php endif; ?>
        </div>
       </div>
      <?php endforeach; ?>
     </div>
    </div>

    <!-- Newsletter Subscription -->


    <!-- Categories Widget -->
    <div class="card border-0 shadow-sm rounded-3">
     <div class="card-header bg-light rounded-top-3 py-3">
      <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Categories</h5>
     </div>
     <div class="card-body p-3">
      <div class="d-flex flex-wrap gap-2">
       <?php foreach ($categories as $cat): ?>
        <a href="<?= base_url('blog/category/' . $cat['id']) ?>" class="badge bg-light text-dark text-decoration-none p-2"><?= $cat['name'] ?></a>
       <?php endforeach; ?>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>

<style>
 .post-content {
  color: #2D3748;
  line-height: 1.7;
 }

 .post-content h2,
 .post-content h3,
 .post-content h4 {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
  color: #1A202C;
 }

 .post-content p {
  margin-bottom: 1.5rem;
 }

 .post-content img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 1.5rem 0;
 }

 .post-content blockquote {
  border-left: 4px solid #4299E1;
  padding-left: 1rem;
  margin-left: 0;
  font-style: italic;
  color: #4A5568;
 }

 .post-content ul,
 .post-content ol {
  margin-bottom: 1.5rem;
  padding-left: 1.5rem;
 }

 .post-content code {
  background-color: #EDF2F7;
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  font-size: 0.9em;
 }

 .post-content pre {
  background-color: #EDF2F7;
  padding: 1rem;
  border-radius: 8px;
  overflow-x: auto;
  margin-bottom: 1.5rem;
 }

 .post-content pre code {
  background: none;
  padding: 0;
 }

 .thumbnail {
  transition: transform 0.3s ease;
 }

 .thumbnail:hover {
  transform: scale(1.01);
 }

 .breadcrumb {
  background-color: transparent;
  padding-left: 0;
 }

 .card-title:hover {
  color: #4299E1 !important;
 }
</style>

<script>
 // Function to calculate reading time (needs to be implemented)
 function calculateReadingTime(content) {
  // Simple calculation: 200 words per minute
  const words = content.split(/\s+/).length;
  return Math.ceil(words / 200);
 }
</script>

<?= $this->endSection() ?>