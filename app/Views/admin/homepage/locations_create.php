<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <form action="<?= site_url('admin/cms/locations/store') ?>" method="post" enctype="multipart/form-data">
      <div class="mb-3">
       <label class="form-label">Location Name</label>
       <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
       <label class="form-label">Flag Image</label>
       <input type="file" name="flag_image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
       <label class="form-label">Thumbnail Image</label>
       <input type="file" name="thumbnail_image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
       <label class="form-label">Extra Info (optional)</label>
       <input type="text" name="location_info" class="form-control" placeholder="/ No GST /">
      </div>

      <div class="mb-3">
       <label class="form-label">Location Link</label>
       <input type="text" name="link" class="form-control" placeholder="https://example.com/warehouse">
      </div>

      <div class="mb-3">
       <label class="form-label">Status</label>
       <select name="status" class="form-select">
        <option value="active">Active</option>
        <option value="coming_soon">Coming Soon</option>
       </select>
      </div>

      <button type="submit" class="btn btn-success">Save Location</button>
      <a href="<?= site_url('admin/locations') ?>" class="btn btn-secondary">Cancel</a>
     </form>



    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>