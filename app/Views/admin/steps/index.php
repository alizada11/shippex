<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <div class="d-flex justify-content-between mb-3">
      <h2>How It Works Steps</h2>
      <a href="<?= site_url('admin/steps/create') ?>" class="btn btn-primary">Add Step</a>
     </div>

     <table class="table table-bordered">
      <thead>
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
         <td>
          <a href="<?= site_url('admin/steps/edit/' . $step['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= site_url('admin/steps/delete/' . $step['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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