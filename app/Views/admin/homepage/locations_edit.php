<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <form action="<?= site_url('admin/cms/locations/update/' . $location['id']) ?>" method="post" enctype="multipart/form-data">
      <div class="mb-3">
       <label class="form-label">Location Name</label>
       <input type="text" name="name" class="form-control" value="<?= esc($location['name']) ?>" required>
      </div>

      <div class="mb-3">
       <label class="form-label">Flag Image</label><br>
       <?php if ($location['flag_image']): ?>
        <img src="<?= base_url('uploads/' . $location['flag_image']) ?>" width="50" class="mb-2"><br>
       <?php endif; ?>
       <input type="file" name="flag_image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
       <label class="form-label">Thumbnail Image</label><br>
       <?php if ($location['thumbnail_image']): ?>
        <img src="<?= base_url('uploads/' . $location['thumbnail_image']) ?>" width="80" class="mb-2"><br>
       <?php endif; ?>
       <input type="file" name="thumbnail_image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
       <label class="form-label">Extra Info</label>
       <input type="text" name="location_info" class="form-control" value="<?= esc($location['location_info']) ?>">
      </div>

      <div class="mb-3">
       <label class="form-label">Location Link</label>
       <input type="text" name="link" class="form-control" value="<?= esc($location['link']) ?>">
      </div>

      <div class="mb-3">
       <label class="form-label">Status</label>
       <select name="status" class="form-select">
        <option value="active" <?= $location['status'] === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="coming_soon" <?= $location['status'] === 'coming_soon' ? 'selected' : '' ?>>Coming Soon</option>
       </select>
      </div>

      <button type="submit" class="btn btn-primary">Update Location</button>
      <a href="<?= site_url('admin/locations') ?>" class="btn btn-secondary">Cancel</a>
     </form>


    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>