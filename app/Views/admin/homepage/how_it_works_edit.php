<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body p-0">
    <div class="row align-items-center">
     <div class="card-header d-flex justify-content-between align-items-center">
      <h5>Edit Step</h5>
     </div>
     <div class="card-body">
      <form action="<?= site_url('admin/cms/how-it-works/update/' . $step['id']) ?>" method="post" enctype="multipart/form-data">
       <div class="mb-3">
        <label>Step Number</label>
        <input type="number" name="step_number" class="form-control" value="<?= esc($step['step_number']) ?>">
       </div>
       <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= esc($step['title']) ?>">
       </div>
       <div class="mb-3">
        <label>Subtitle</label>
        <input type="text" name="subtitle" class="form-control" value="<?= esc($step['subtitle']) ?>">
       </div>
       <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"><?= esc($step['description']) ?></textarea>
       </div>
       <div class="mb-3">
        <label>Icon</label>
        <input type="file" name="icon" class="form-control">
        <?php if ($step['icon']): ?>
         <img src="<?= base_url($step['icon']) ?>" width="40" class="mt-2">
        <?php endif; ?>
       </div>
       <hr>
       <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('admin/cms/how-it-works') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
       </div>
      </form>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>