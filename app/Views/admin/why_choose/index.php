<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">

    <div class="card-header d-flex align-items-center justify-content-between ">
     <h5 class="mb-0">Why Choose Us</h5>
     <a href="<?= site_url('admin/cms/why-choose/create') ?>" class="btn btn-shippex-orange"><i class="fas fa-plus"></i> Add Item</a>
    </div>
    <div class="card-body p-0 table-responsive">

     <table class="table table-bordered">
      <thead class="table-light">
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

         <td class="actions-col">
          <div class="action-buttons-table">
           <a href="<?= site_url('admin/cms/why-choose/edit/' . $item['id']) ?>" class="btn btn-action view">
            <i class="fas fa-eye "></i>
           </a>

           <form action="<?= site_url('admin/cms/why-choose/delete/' . $item['id']) ?>" class="delete-form" method="post">
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

<?= $this->endSection() ?>