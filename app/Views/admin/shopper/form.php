<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="container py-4">
   <?= csrf_field() ?>
   <div class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
     <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
     <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (isset($errors) && is_array($errors)): ?>
     <div class="alert alert-danger">
      <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
     </div>
    <?php endif; ?>

    <div class="card">
     <div class="card-body">
      <?= form_open('/shopper/submit', ['id' => 'shopper-add-form', 'class' => 'form-horizontal']) ?>
      <div class="items" data-items>
       <div class="item" data-item>
        <div class="form-group row mb-2">
         <h5 class="col-12 text-primary">Item <span class="item-index">1</span></h5>
        </div>

        <div class="form-group row mb-2">
         <label class="col-sm-4 col-md-3 col-form-label">Item name<span class="text-danger">*</span></label>
         <div class="col-sm-8 col-md-9">
          <input name="name[]" class="form-control" placeholder="Item name" required minlength="2">
         </div>
        </div>

        <div class="form-group row mb-2">
         <label class="col-sm-4 col-md-3 col-form-label">Item url<span class="text-danger">*</span></label>
         <div class="col-sm-8 col-md-9">
          <input name="url[]" class="form-control" placeholder="Item url" required type="url">
         </div>
        </div>

        <div class="form-group row mb-2">
         <label class="col-sm-4 col-md-3 col-form-label">Item size</label>
         <div class="col-sm-8 col-md-3">
          <input name="size[]" class="form-control" placeholder="if applicable">
         </div>
         <label class="col-sm-4 col-md-3 col-form-label">Item colour</label>
         <div class="col-sm-8 col-md-3">
          <input name="color[]" class="form-control" placeholder="if applicable">
         </div>
        </div>

        <div class="form-group row mb-2">
         <label class="col-sm-4 col-md-3 col-form-label">Additional instructions</label>
         <div class="col-sm-8 col-md-9">
          <textarea name="instructions[]" class="form-control" placeholder="e.g. 'Please gift wrap'"></textarea>
         </div>
        </div>

        <div class="form-group row mb-2 align-items-center">
         <label class="col-sm-4 col-md-3 col-form-label">Quantity<span class="text-danger">*</span></label>
         <div class="col-sm-4 col-md-4">
          <input name="quantity[]" type="number" min="1" value="1" class="form-control" required>
         </div>
         <div class="col-sm-4 col-md-3 remove-btn-container">
          <!-- Remove button will be inserted dynamically for all except the first item -->
         </div>
        </div>


       </div>
      </div>

      <div class="form-group row mb-2 border-top border-bottom py-2">
       <div class="col-12 col-md-4">
        <button type="button" class="add-item btn btn-block btn-info">Add item</button>
       </div>
      </div>

      <div class="form-group row mb-2">
       <label class="col-sm-4 col-md-3 col-form-label">Shipping Option</label>
       <div class="col-sm-8 col-md-9">
        <input name="delivery_description" class="form-control" placeholder="e.g. 'First Class'">
       </div>
      </div>

      <div class="form-group row mb-2">
       <label class="col-sm-4 col-md-3 col-form-label">Shipping Preference</label>
       <div class="col-sm-8 col-md-9">
        <input name="delivery_notes" class="form-control" placeholder="e.g. 'Send everything together'">
       </div>
      </div>

      <div class="form-group row mb-2">
       <div class="col-sm-8 col-md-9 offset-sm-3">
        <input type="checkbox" id="allow-alternate-retailers" name="use_another_retailer" value="yes">
        <label for="allow-alternate-retailers">Allow alternate retailers</label>
       </div>
      </div>

      <div class="row justify-content-between">
       <div class="col-sm-6 col-md-4">
        <button type="submit" name="submit" value="submit" class="btn btn-block btn-secondary text-uppercase">Submit Request</button>
       </div>
       <div class="col-sm-6 col-md-4 text-end">
        <button type="submit" name="submit" value="save" class="btn btn-block btn-warning text-white text-uppercase">Save for later</button>
       </div>
      </div>

      <?= form_close() ?>
     </div>
    </div>
   </div>
  </div>
 </div>

 <?= $this->section('script') ?>
 <script>
  $(function() {
   // Add / remove items
   $(function() {
    function reindex() {
     $('[data-item]').each(function(i) {
      $(this).find('.item-index').text(i + 1);

      let btnContainer = $(this).find('.remove-btn-container');
      btnContainer.empty(); // Clear any existing button

      if (i > 0) {
       btnContainer.html('<button type="button" class="btn btn-danger btn-block remove-item">Remove item</button>');
      }
     });
    }

    $('.add-item').on('click', function() {
     let $clone = $('[data-item]').first().clone();
     $clone.find('input, textarea').val('');
     $clone.find('input[type=number]').val(1);
     $clone.appendTo('[data-items]');
     reindex();
    });

    $(document).on('click', '.remove-item', function() {
     $(this).closest('[data-item]').remove();
     reindex();
    });

    reindex();
   });


  });
 </script>
 <?= $this->endSection() ?>

 <?= $this->endSection() ?>