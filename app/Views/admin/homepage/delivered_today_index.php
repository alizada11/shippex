<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body p-0">
    <div class="row align-items-center">
     <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Delivered Today Items</h5>
      <a href="<?= site_url('admin/cms/delivered-today/create') ?>" class="btn btn-shippex-orange"><i class="fas fa-plus"></i> Add New</a>

     </div>

     <div class="card-body p-0 table-responsive">
      <table class="table table-bordered">
       <thead class="table-light">
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
          <td><img src="<?= base_url($item['courier_logo']) ?? 'uploads/default.jpg' ?>" height="30"></td>
          <td><img src="<?= base_url($item['retailer_logo']) ?? 'uploads/default.jpg' ?>" height="30"></td>
          <td>
           <img src="<?= base_url($item['from_flag']) ?? 'uploads/default.jpg' ?>" height="20"> <?= $item['from_country'] ?>
           →
           <img src="<?= base_url($item['to_flag']) ?? 'uploads/default.jpg' ?>" height="20"> <?= $item['to_country'] ?>
          </td>
          <td><?= esc($item['cost']) ?></td>
          <td><?= esc($item['weight']) ?></td>


          <td class="actions-col">
           <div class="action-buttons-table">
            <a href="<?= site_url('admin/cms/delivered-today/edit/' . $item['id']) ?>" class="btn btn-action edit">
             <i class="fas fa-edit "></i>
            </a>

            <form action="<?= site_url('admin/cms/delivered-today/delete/' . $item['id']) ?>" class="delete-form" method="post">
             <?= csrf_field() ?>
             <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></button>
            </form>
           </div>
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
</div>

<?= $this->endSection() ?>