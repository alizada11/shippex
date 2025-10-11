<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <h2>Edit Delivered Today Item</h2>

     <form action="<?= site_url('admin/cms/delivered-today/update/' . $item['id']) ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>Courier Logo</label><br>
        <?php if ($item['courier_logo']): ?>
         <img src="<?= base_url($item['courier_logo']) ?>" height="40" class="mb-2"><br>
        <?php endif; ?>
        <input type="file" name="courier_logo" class="form-control">
       </div>
       <div class="col-md-6 mb-3">
        <label>Retailer Logo</label><br>
        <?php if ($item['retailer_logo']): ?>
         <img src="<?= base_url($item['retailer_logo']) ?>" height="40" class="mb-2"><br>
        <?php endif; ?>
        <input type="file" name="retailer_logo" class="form-control">
       </div>
      </div>

      <div class="mb-3">
       <label>FontAwesome Icon</label>
       <input type="text" name="icon" value="<?= esc($item['icon']) ?>" class="form-control">
      </div>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>From Country</label>
        <input type="text" name="from_country" value="<?= esc($item['from_country']) ?>" class="form-control">
        <label>From Flag</label><br>
        <?php if ($item['from_flag']): ?>
         <img src="<?= base_url($item['from_flag']) ?>" height="20" class="mb-2"><br>
        <?php endif; ?>
        <input type="file" name="from_flag" class="form-control">
       </div>
       <div class="col-md-6 mb-3">
        <label>To Country</label>
        <input type="text" name="to_country" value="<?= esc($item['to_country']) ?>" class="form-control">
        <label>To Flag</label><br>
        <?php if ($item['to_flag']): ?>
         <img src="<?= base_url($item['to_flag']) ?>" height="20" class="mb-2"><br>
        <?php endif; ?>
        <input type="file" name="to_flag" class="form-control">
       </div>
      </div>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>Cost</label>
        <input type="text" name="cost" value="<?= esc($item['cost']) ?>" class="form-control">
       </div>
       <div class="col-md-6 mb-3">
        <label>Weight</label>
        <input type="text" name="weight" value="<?= esc($item['weight']) ?>" class="form-control">
       </div>
      </div>

      <button class="btn btn-primary">Update</button>
      <a href="<?= site_url('admin/cms/delivered-today') ?>" class="btn btn-secondary">Cancel</a>
     </form>
    </div>

   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>