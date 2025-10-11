<div class="container">
 <h2>Edit Section</h2>
 <form action="<?= site_url('admin/how-it-works/update/' . $section['id']) ?>" method="post" enctype="multipart/form-data">
  <div class="mb-3">
   <label>Title</label>
   <input type="text" name="title" class="form-control" value="<?= esc($section['title']) ?>">
  </div>
  <div class="mb-3">
   <label>Description</label>
   <textarea name="description" class="form-control" rows="4"><?= esc($section['description']) ?></textarea>
  </div>
  <div class="mb-3">
   <label>Image</label>
   <input type="file" name="image" class="form-control">
   <?php if ($section['image']): ?>
    <img src="<?= base_url('writable/uploads/' . $section['image']) ?>" width="150">
   <?php endif; ?>
  </div>
  <div class="mb-3">
   <label>Button Text</label>
   <input type="text" name="button_text" class="form-control" value="<?= esc($section['button_text']) ?>">
  </div>
  <div class="mb-3">
   <label>Button Link</label>
   <input type="text" name="button_link" class="form-control" value="<?= esc($section['button_link']) ?>">
  </div>
  <div class="mb-3">
   <label>Order</label>
   <input type="number" name="order" class="form-control" value="<?= esc($section['order']) ?>">
  </div>
  <button class="btn btn-primary">Update</button>
 </form>
</div>