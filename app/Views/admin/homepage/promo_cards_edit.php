<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">
    <div class="card-header border-0 align-items-center d-flex justify-content-between">
     <h5 class="mb-0">Edit Promotion Card</h5>

    </div>
    <div class="card-body">
     <form action="<?= isset($card) ? site_url('/admin/cms/promo-cards/update/' . $card['id']) : site_url('/admin/cms/promo-cards/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="mb-3">
       <label>Title</label>
       <input type="text" name="title" class="form-control" value="<?= isset($card) ? esc($card['title']) : old('title') ?>" required>
      </div>

      <div class="mb-3">
       <label>Description</label>
       <textarea name="description" class="form-control" rows="4" required><?= isset($card) ? esc($card['description']) : old('description') ?></textarea>
      </div>

      <div class="mb-3">
       <label>Button Text</label>
       <input type="text" name="button_text" class="form-control" value="<?= isset($card) ? esc($card['button_text']) : old('button_text') ?>" required>
      </div>

      <div class="mb-3">
       <label>Button URL</label>
       <input type="url" name="button_url" class="form-control" value="<?= isset($card) ? esc($card['button_url']) : old('button_url') ?>" required>
      </div>

      <div class="mb-3">
       <label>Image</label>
       <input type="file" name="image" class="form-control">
       <?php if (isset($card) && $card['image']): ?>
        <img src="<?= base_url('uploads/promo_cards/' . $card['image']) ?>" width="100" class="mt-2">
       <?php endif; ?>
      </div>
      <div class="mb-3">
       <label>Background Image</label>
       <input type="file" name="background" class="form-control">
       <?php if (isset($card) && $card['background']): ?>
        <img src="<?= base_url('uploads/promo_cards/' . $card['background']) ?>" width="100" class="mt-2">
       <?php endif; ?>
      </div>
      <hr>
      <div class="d-flex justify-content-between">
       <button type="submit" class="btn btn-primary"><?= isset($card) ? 'Update' : 'Create' ?></button>
       <a href="<?= site_url('admin/cms/promo-cards') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
      </div>
     </form>



    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>