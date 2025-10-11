<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">

     <form action="<?= site_url('admin/cms/hero-section/update') ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $hero['id'] ?>">

      <div class="mb-3">
       <label>Title</label>
       <input type="text" name="title" value="<?= esc($hero['title']) ?>" class="form-control">
      </div>

      <div class="mb-3">
       <label>Subtitle</label>
       <input type="text" name="subtitle" value="<?= esc($hero['subtitle']) ?>" class="form-control">
      </div>

      <div class="mb-3">
       <label>Description</label>
       <textarea name="description" class="form-control"><?= esc($hero['description']) ?></textarea>
      </div>

      <div class="mb-3">
       <label>Button Text</label>
       <input type="text" name="button_text" value="<?= esc($hero['button_text']) ?>" class="form-control">
      </div>

      <div class="mb-3">
       <label>Button Link</label>
       <input type="text" name="button_link" value="<?= esc($hero['button_link']) ?>" class="form-control">
      </div>

      <div class="mb-3">
       <label>Background Image</label>
       <input type="file" name="background_image" class="form-control">
       <?php if ($hero['background_image']): ?>
        <img src="<?= base_url($hero['background_image']) ?>" width="200" class="mt-2">
       <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
     </form>

    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>