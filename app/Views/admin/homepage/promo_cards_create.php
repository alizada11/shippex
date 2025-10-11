<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <form action="<?= isset($card) ? site_url('/admin/cms/promo-cards/update/' . $card['id']) : site_url('/admin/cms/promo-cards/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="mb-3">
       <label>Titl</label>
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
        <img src="<?= base_url('writable/uploads/promo_cards/' . $card['image']) ?>" width="100" class="mt-2">
       <?php endif; ?>
      </div>
      <div class="mb-3">
       <label>Background Image</label>
       <input type="file" name="background" class="form-control">
       <?php if (isset($card) && $card['background']): ?>
        <img src="<?= base_url('writable/uploads/promo_cards/' . $card['background']) ?>" width="150" class="mt-2">
       <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-success"><?= isset($card) ? 'Update' : 'Create' ?></button>
     </form>



    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>