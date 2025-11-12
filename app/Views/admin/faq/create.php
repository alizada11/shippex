<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>



<!-- Stats Overview -->



<div class="card p-0 container shadow-sm">
 <div class="card-header border-0">
  <h5>Add New FAQ</h5>

 </div>
 <div class="card-body">
  <form action="<?= base_url('admin/faqs/store') ?>" method="post">
   <div class="mb-3">
    <label>Question</label>
    <input type="text" name="question" class="form-control" required>
   </div>
   <div class="mb-3">
    <label>Answer</label>
    <textarea name="answer" rows="5" class="form-control" required></textarea>
   </div>
   <hr>
   <div class="d-flex align-items-center justify-content-between">

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="<?= base_url('admin/faqs') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
   </div>
  </form>
 </div>

</div>

<?= $this->endSection() ?>