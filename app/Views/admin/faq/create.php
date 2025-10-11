<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="card p-3">
 <!-- Stats Overview -->
 <div class="row">


  <div class="container ">
   <h3>Add New FAQ</h3>
   <form action="<?= base_url('admin/faqs/store') ?>" method="post">
    <div class="mb-3">
     <label>Question</label>
     <input type="text" name="question" class="form-control" required>
    </div>
    <div class="mb-3">
     <label>Answer</label>
     <textarea name="answer" rows="5" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn text-white bg-shippex-purple">Save</button>
    <a href="<?= base_url('admin/faqs') ?>" class="btn btn-secondary">Cancel</a>
   </form>
  </div>
 </div>
</div>

<?= $this->endSection() ?>