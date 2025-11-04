<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="container py-4">
   <div class="card shadow-sm">
    <div class="card-header bg-shippex-purple text-white d-flex justify-content-between align-items-center">

     <h2>All Virtual Addresses</h2>
     <a href="/warehouse/create" class="btn btn-primary mb-3">Add New Address</a>

    </div>
    <div class="card-body p-0">
     <div class="table-responsive">


      <table class="table  table-hover mb-0">
       <thead>
        <tr>
         <th> ID</th>
         <th>Country</th>
         <th>Address</th>
         <th>Postal Code</th>
         <th>Phone</th>
         <th>Is Active</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php
        $i = 1;
        foreach ($addresses as $addr): ?>
         <tr>
          <td><?= $i ?></td>
          <td><?= esc($addr['country']) ?></td>
          <td><?= esc($addr['address_line']) ?></td>
          <td><?= esc($addr['postal_code']) ?></td>
          <td><?= esc($addr['phone']) ?></td>
          <td><?= $addr['is_active'] ? 'Yes' : 'No' ?></td>
          <td class="d-flex gap-2">
           <a href="/warehouse/edit/<?= $addr['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
           <a href="/warehouse/delete/<?= $addr['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
          </td>
         </tr>

        <?php
         $i++;
        endforeach; ?>
       </tbody>
      </table>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>