<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">

 <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
   <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
   <li class="breadcrumb-item active" aria-current="page">Blogs</li>
  </ol>
 </nav>
 <h1 class="mb-4">Business Blogs</h1>

 <?php if (empty($posts)): ?>
  <div class="alert alert-info">No posts found.</div>
 <?php else: ?>
  <div class="row g-4">
   <?php foreach ($posts as $p): ?>
    <div class="col-md-3">
     <div class="card h-100 shadow-sm">
      <?php if (! empty($p->thumbnail)): ?>
       <a href="<?= site_url('blog/' . esc($p->slug)) ?>">
        <img src="<?= base_url('uploads/blog/' . $p->thumbnail) ?>" class="card-img-top" alt="<?= esc($p->title) ?>">
       </a>
      <?php endif; ?>
      <div class="card-body d-flex flex-column">
       <h5 class="card-title">
        <a href="<?= site_url('blog/' . esc($p->slug)) ?>" class="text-decoration-none">
         <?= esc($p->title) ?>
        </a>
       </h5>
       <p class="text-muted small mb-2">
        <?= esc(date('F j, Y', strtotime($p->published_at ?? $p->created_at))) ?>
       </p>
       <p class="card-text">
        <?= esc($p->excerpt ?? (mb_substr(strip_tags($p->content), 0, 120) . '...')) ?>
       </p>
       <div class="mt-auto">
        <a href="<?= site_url('blog/' . esc($p->slug)) ?>" class="btn btn-sm bg-shippex-purple text-white">Read more</a>
       </div>
      </div>
     </div>
    </div>
   <?php endforeach; ?>
  </div>

  <div class="row mt-4">
   <?= $pager->links('default', 'bootstrap_full') ?>

  </div>
 <?php endif; ?>
</div>
<?= $this->endSection() ?>