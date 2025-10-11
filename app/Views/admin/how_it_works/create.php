<div class="container">
 <h2>Create Section</h2>
 <form action="<?= site_url('admin/how-it-works/store') ?>" method="post" enctype="multipart/form-data">
  <div class="mb-3">
   <label>Title</label>
   <input type="text" name="title" class="form-control">
  </div>
  <div class="mb-3">
   <label>Description</label>
   <textarea name="description" class="form-control" rows="4"></textarea>
  </div>
  <div class="mb-3">
   <label>Image</label>
   <input type="file" name="image" class="form-control">
  </div>
  <div class="mb-3">
   <label>Button Text</label>
   <input type="text" name="button_text" class="form-control">
  </div>
  <div class="mb-3">
   <label>Button Link</label>
   <input type="text" name="button_link" class="form-control">
  </div>
  <div class="mb-3">
   <label>Order</label>
   <input type="number" name="order" class="form-control" value="0">
  </div>
  <button class="btn btn-primary">Save</button>
 </form>
</div>