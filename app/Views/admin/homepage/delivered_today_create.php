<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">
    <div class="card-header border-0">

     <h5>Add Delivered Today Item</h5>
    </div>
    <div class="card-body">

     <form action="<?= site_url('admin/cms/delivered-today/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>Courier Logo</label>
        <input type="file" name="courier_logo" class="form-control">
        <?php if (isset($old['courier_logo']) && $old['courier_logo']): ?>
         <div class="mt-2">
          <img src="<?= base_url($old['courier_logo']) ?>" alt="Courier Logo" width="80">
         </div>
        <?php endif; ?>
       </div>

       <div class="col-md-6 mb-3">
        <label>Retailer Logo</label>
        <input type="file" name="retailer_logo" class="form-control">
        <?php if (isset($old['retailer_logo']) && $old['retailer_logo']): ?>
         <div class="mt-2">
          <img src="<?= base_url($old['retailer_logo']) ?>" alt="Retailer Logo" width="80">
         </div>
        <?php endif; ?>
       </div>
      </div>

      <div class="mb-3">
       <label>FontAwesome Icon (e.g. fas fa-laptop)</label>
       <input type="text" name="icon" class="form-control" value="<?= old('icon') ?>">
      </div>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>From Country</label>
        <input type="text" name="from_country" class="form-control" value="<?= old('from_country') ?>">
        <label>From Flag</label>
        <input type="file" name="from_flag" class="form-control">
        <?php if (isset($old['from_flag']) && $old['from_flag']): ?>
         <div class="mt-2">
          <img src="<?= base_url($old['from_flag']) ?>" alt="From Flag" width="60">
         </div>
        <?php endif; ?>
       </div>

       <div class="col-md-6 mb-3">
        <label>To Country</label>
        <input type="text" name="to_country" class="form-control" value="<?= old('to_country') ?>">
        <label>To Flag</label>
        <input type="file" name="to_flag" class="form-control">
        <?php if (isset($old['to_flag']) && $old['to_flag']): ?>
         <div class="mt-2">
          <img src="<?= base_url($old['to_flag']) ?>" alt="To Flag" width="60">
         </div>
        <?php endif; ?>
       </div>
      </div>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>Cost</label>
        <input type="text" name="cost" class="form-control" value="<?= old('cost') ?>">
       </div>
       <div class="col-md-6 mb-3">
        <label>Weight</label>
        <input type="text" name="weight" class="form-control" value="<?= old('weight') ?>">
       </div>
      </div>

      <hr>
      <div class="d-flex justify-content-between">
       <button class="btn btn-primary" type="submit">Create</button>
       <a href="<?= site_url('admin/cms/delivered-today') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
       </a>
      </div>
     </form>

    </div>


   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>