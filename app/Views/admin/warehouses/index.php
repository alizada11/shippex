<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="container ">
   <div class="card p-0 shadow-sm">
    <div class="card-header bg-shippex-purple text-white d-flex justify-content-between align-items-center">

     <h5>All Virtual Addresses</h5>
     <a href="/warehouse/create" class="btn btn-shippex-orange "><i class="fas fa-plus"></i> Add New Address</a>

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
          <td><?= esc($addr['address_line_1']) . ' ' . esc($addr['address_line_1']) ?></td>
          <td><?= esc($addr['postal_code']) ?></td>
          <td><?= esc($addr['phone']) ?></td>
          <td><?= $addr['is_active'] ? 'Yes' : 'No' ?></td>
          <td class="actions-col">
           <div class="action-buttons-table">
            <a href="/warehouse/edit/<?= $addr['id'] ?>" class="btn btn-action view"><i class="fas fa-eye"></i></a>
            <form class="delete-form" action="<?= base_url('/warehouse/delete/' . $addr['id']) ?>" method="post" class="d-inline delete-form">
             <?= csrf_field() ?>
             <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></a>
            </form>
           </div>
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