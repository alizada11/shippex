<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">
    <div class="card-header border-0">

     <h5><?= isset($step['id']) ? 'Edit Step ' . $step['id'] : 'Add New Step' ?></h5>

    </div>

    <div class="card-body ">

     <form method="post" enctype="multipart/form-data" action="<?= isset($step) ? site_url('/admin/cms/steps/edit/' . $step['id']) : site_url('/admin/cms/steps/create') ?>">
      <div class="mb-3">
       <label class="form-label">Title</label>
       <input type="text" name="title" class="form-control" value="<?= $step['title'] ?? '' ?>" required>
      </div>

      <div class="mb-3">
       <label class="form-label">Description</label>
       <textarea name="description" class="form-control" rows="4" required><?= $step['description'] ?? '' ?></textarea>
      </div>

      <div class="mb-3">
       <label class="form-label">Image</label>
       <input type="file" name="image" class="form-control">
       <?php if (!empty($step['image'])): ?>
        <img src="<?= base_url('uploads/' . $step['image']) ?>" width="120" class="mt-2">
       <?php endif; ?>
      </div>

      <div class="mb-3">
       <label class="form-label">Order</label>
       <input type="number" name="order" class="form-control" value="<?= $step['order'] ?? 0 ?>">
      </div>
      <div class="d-flex align-items-center justify-content-between">
       <button type="submit" class="btn btn-primary">Save</button>
       <a href="<?= site_url('admin/cms/steps') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
      </div>
     </form>
    </div>
   </div>
  </div>
 </div>

 <?= $this->endSection() ?>