<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <h2>Add Delivered Today Item</h2>

     <form action="<?= site_url('admin/cms/delivered-today/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>Courier Logo</label>
        <input type="file" name="courier_logo" class="form-control">
       </div>
       <div class="col-md-6 mb-3">
        <label>Retailer Logo</label>
        <input type="file" name="retailer_logo" class="form-control">
       </div>
      </div>

      <div class="mb-3">
       <label>FontAwesome Icon (e.g. fas fa-laptop)</label>
       <input type="text" name="icon" class="form-control">
      </div>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>From Country</label>
        <input type="text" name="from_country" class="form-control">
        <label>From Flag</label>
        <input type="file" name="from_flag" class="form-control">
       </div>
       <div class="col-md-6 mb-3">
        <label>To Country</label>
        <input type="text" name="to_country" class="form-control">
        <label>To Flag</label>
        <input type="file" name="to_flag" class="form-control">
       </div>
      </div>

      <div class="row">
       <div class="col-md-6 mb-3">
        <label>Cost</label>
        <input type="text" name="cost" class="form-control">
       </div>
       <div class="col-md-6 mb-3">
        <label>Weight</label>
        <input type="text" name="weight" class="form-control">
       </div>
      </div>

      <button class="btn btn-success">Save</button>
      <a href="<?= site_url('admin/cms/delivered-today') ?>" class="btn btn-secondary">Cancel</a>
     </form>
    </div>


   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>