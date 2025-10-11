<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">
     <h2>Add New Step</h2>
     <form action="<?= site_url('admin/cms/how-it-works/store') ?>" method="post" enctype="multipart/form-data">
      <div class="mb-3">
       <label>Step Number</label>
       <input type="number" name="step_number" class="form-control">
      </div>
      <div class="mb-3">
       <label>Subtitle</label>
       <input type="text" name="subtitle" class="form-control">
      </div>
      <div class="mb-3">
       <label>Title</label>
       <input type="text" name="title" class="form-control">
      </div>
      <div class="mb-3">
       <label>Description</label>
       <textarea name="description" class="form-control"></textarea>
      </div>
      <div class="mb-3">
       <label>Icon</label>
       <input type="file" name="icon" class="form-control">
      </div>
      <button type="submit" class="btn btn-success">Save</button>
     </form>


    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>