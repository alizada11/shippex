<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">
    <div class="card-header">
     <h5><?= isset($item['id']) ? 'Edit Item ' . $item['id'] : 'Add New Item' ?></h5>

    </div>
    <div class="card-body">

     <form method="post" enctype="multipart/form-data" action="<?= isset($item) ? site_url('/admin/cms/why-choose/edit/' . $item['id']) : site_url('/admin/cms/why-choose/create') ?>">
      <div class="mb-3">
       <label class="form-label">Title</label>
       <input type="text" name="title" class="form-control" value="<?= $item['title'] ?? '' ?>" required>
      </div>

      <div class="mb-3">
       <label class="form-label">Description</label>
       <textarea name="description" class="form-control" rows="3" required><?= $item['description'] ?? '' ?></textarea>
      </div>

      <div class="mb-3">
       <label class="form-label">Icon</label>
       <input type="file" name="icon" class="form-control">
       <?php if (!empty($item['icon'])): ?>
        <img src="<?= base_url('uploads/why_choose/' . $item['icon']) ?>" width="40" class="mt-2">
       <?php endif; ?>
      </div>

      <div class="mb-3">
       <label class="form-label">Order</label>
       <input type="number" name="order" class="form-control" value="<?= $item['order'] ?? 0 ?>">
      </div>
      <div class="d-flex align-items-center justify-content-between">

       <button type="submit" class="btn btn-primary">Save</button>
       <a href="<?= site_url('admin/cms/why-choose') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
      </div>
     </form>
    </div>
   </div>
  </div>
 </div>

 <?= $this->endSection() ?>