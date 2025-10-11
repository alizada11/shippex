<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <h2>How It Works Abolfazl</h2>
     <a href="<?= site_url('admin/cms/how-it-works/create') ?>" class="btn btn-primary mb-3">Add Step</a>

     <table class="table table-bordered">
      <thead>
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
         <td>
          <a href="<?= site_url('admin/cms/how-it-works/edit/' . $step['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= site_url('admin/cms/how-it-works/delete/' . $step['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this step?')">Delete</a>
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