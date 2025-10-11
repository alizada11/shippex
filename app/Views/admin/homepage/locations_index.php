<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <div class="mb-4 d-flex justify-content-between">
      <h2 class="mb-0">Manage Warehouse Locations</h2>

      <a href="<?= site_url('admin/cms/locations/create') ?>" class="btn btn-shippex mb-3">➕ Add New Location</a>

     </div>
     <table class="table table-bordered table-striped">
      <thead>
       <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Flag</th>
        <th>Thumbnail</th>
        <th>Info</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php if (!empty($locations)): ?>
        <?php foreach ($locations as $loc): ?>
         <tr>
          <td><?= esc($loc['id']) ?></td>
          <td><?= esc($loc['name']) ?></td>
          <td><span class="badge bg-<?= $loc['status'] === 'active' ? 'success' : 'secondary' ?>">
            <?= esc(ucfirst($loc['status'])) ?>
           </span></td>
          <td><img src="<?= base_url('uploads/' . $loc['flag_image']) ?>" alt="Flag" width="40"></td>
          <td><img src="<?= base_url('uploads/' . $loc['thumbnail_image']) ?>" alt="Thumbnail" width="60"></td>
          <td><?= esc($loc['location_info']) ?></td>
          <td>
           <a href="<?= site_url('admin/cms/locations/edit/' . $loc['id']) ?>" class="btn btn-sm btn-primary">✏ Edit</a>
           <a href="<?= site_url('admin/cms/locations/delete/' . $loc['id']) ?>"
            onclick="return confirm('Are you sure you want to delete this location?')"
            class="btn btn-sm btn-danger">🗑 Delete</a>
          </td>
         </tr>
        <?php endforeach; ?>
       <?php else: ?>
        <tr>
         <td colspan="7" class="text-center">No locations added yet.</td>
        </tr>
       <?php endif; ?>
      </tbody>
     </table>
    </div>



   </div>
  </div>
 </div>
</div>
</div>

<?= $this->endSection() ?>