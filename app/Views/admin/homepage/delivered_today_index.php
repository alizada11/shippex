<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <div class="mb-4 d-flex justify-content-between">
      <h2 class="mb-0">Delivered Today Items</h2>
      <a href="<?= site_url('admin/cms/delivered-today/create') ?>" class="btn btn-success mb-3">+ Add New</a>

     </div>
     <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
     <?php endif; ?>

     <table class="table table-bordered">
      <thead>
       <tr>
        <th>ID</th>
        <th>Courier</th>
        <th>Retailer</th>
        <th>From → To</th>
        <th>Cost</th>
        <th>Weight</th>
        <th>Action</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ($items as $item): ?>
        <tr>
         <td><?= $item['id'] ?></td>
         <td><img src="<?= base_url($item['courier_logo']) ?>" height="30"></td>
         <td><img src="<?= base_url($item['retailer_logo']) ?>" height="30"></td>
         <td>
          <img src="<?= base_url($item['from_flag']) ?>" height="20"> <?= $item['from_country'] ?>
          →
          <img src="<?= base_url($item['to_flag']) ?>" height="20"> <?= $item['to_country'] ?>
         </td>
         <td><?= esc($item['cost']) ?></td>
         <td><?= esc($item['weight']) ?></td>
         <td>
          <a href="<?= site_url('admin/delivered-today/edit/' . $item['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="<?= site_url('admin/delivered-today/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</a>
         </td>
        </tr>
       <?php endforeach; ?>
      </tbody>
     </table>
    </div>

   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>