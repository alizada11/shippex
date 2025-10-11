<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">

     <div class="d-flex mb-3 justify-content-between mb-3">
      <h2 class="mb-0">Why Choose Us</h2>
      <a href="<?= site_url('admin/cms/why-choose/create') ?>" class="btn btn-primary">Add Item</a>
     </div>

     <table class="table table-bordered">
      <thead>
       <tr>
        <th>Order</th>
        <th>Title</th>
        <th>Icon</th>
        <th>Description</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ($items as $item): ?>
        <tr>
         <td><?= esc($item['order']) ?></td>
         <td><?= esc($item['title']) ?></td>
         <td>
          <?php if ($item['icon']): ?>
           <img src="<?= base_url('uploads/why_choose/' . $item['icon']) ?>" width="40">
          <?php endif; ?>
         </td>
         <td><?= esc($item['description']) ?></td>
         <td class="d-flex gap-1 justify-content-between">
          <a href="<?= site_url('admin/cms/why-choose/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= site_url('admin/cms/why-choose/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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