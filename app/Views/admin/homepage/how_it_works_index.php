<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body p-0">
    <div class="row align-items-center">
     <div class="card-header d-flex justify-content-between align-items-center">
      <h5>How It Works </h5>
      <a href="<?= site_url('admin/cms/how-it-works/create') ?>" class="btn btn-shippex-orange">Add Step</a>
     </div>
     <div class="table-responsive">
      <table class="table table-bordered">
       <thead class="table-light">
        <tr>
         <th>#</th>
         <th>Subtitle</th>
         <th>Title</th>
         <th>Description</th>
         <th>Icon</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php foreach ($steps as $step): ?>
         <tr>
          <td><?= esc($step['id']) ?></td>
          <td><?= esc($step['subtitle']) ?></td>
          <td><?= esc($step['title']) ?></td>
          <td><?= esc($step['description']) ?></td>
          <td>
           <?php if ($step['icon']): ?>
            <img src="<?= base_url($step['icon']) ?>" width="40">
           <?php endif; ?>
          </td>
          <td class="actions-col">
           <div class="action-buttons-table">
            <a href="<?= site_url('admin/cms/how-it-works/edit/' . $step['id']) ?>" class="btn btn-action edit"><i class="fas fa-edit"></i></a>
            <form class="delete-form" action="<?= site_url('admin/cms/how-it-works/delete/' . $step['id']) ?>" method="post" class="d-inline delete-form">
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