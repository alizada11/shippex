<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">


  <div class="card p-3 ">
   <div class="d-flex justify-content-between">

    <h3>FAQ Management</h3>
    <a href="<?= base_url('admin/faqs/create') ?>" class="btn text-white bg-shippex-purple mb-3">+ Add FAQ</a>
   </div>

   <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
   <?php endif; ?>

   <table class="table table-bordered">
    <thead class="table-light">
     <tr>
      <th>#</th>
      <th>Question</th>
      <th>Answer</th>
      <th>Actions</th>
     </tr>
    </thead>
    <tbody>
     <?php foreach ($faqs as $index => $faq): ?>
      <tr>
       <td><?= $index + 1 ?></td>
       <td><?= esc($faq['question']) ?></td>
       <td><?= esc($faq['answer']) ?></td>
       <td class="d-flex gap-1">
        <a href="<?= base_url('admin/faqs/edit/' . $faq['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="<?= base_url('admin/faqs/delete/' . $faq['id']) ?>" class="btn btn-sm btn-danger"
         onclick="return confirm('Are you sure you want to delete this FAQ?')">Delete</a>
       </td>
      </tr>
     <?php endforeach; ?>
    </tbody>
   </table>
  </div>
 </div>
</div>

<?= $this->endSection() ?>