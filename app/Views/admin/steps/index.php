<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">
    <div class="card-header align-items-center d-flex justify-content-between">
     <h5>How It Works Steps</h5>
     <a href="<?= site_url('admin/cms/steps/create') ?>" class="btn btn-shippex-orange"><i class="fas fa-plus"></i> Add Step</a>
    </div>
    <div class="card-body p-0">

     <table class="table table-bordered">
      <thead class="table-light">
       <tr>
        <th>Order</th>
        <th>Title</th>
        <th>Image</th>
        <th>Description</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ($steps as $step): ?>
        <tr>
         <td><?= esc($step['order']) ?></td>
         <td><?= esc($step['title']) ?></td>
         <td>
          <?php if ($step['image']): ?>
           <img src="<?= base_url('uploads/' . $step['image']) ?>" width="80">
          <?php endif; ?>
         </td>
         <td><?= esc($step['description']) ?></td>

         <td class="actions-col">
          <div class="action-buttons-table">
           <a href="<?= site_url('admin/cms/steps/edit/' . $step['id']) ?>" class="btn btn-action view">
            <i class="fas fa-eye "></i>
           </a>

           <form action="<?= site_url('admin/cms/steps/delete/' . $step['id']) ?>" class="delete-form" method="post">
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