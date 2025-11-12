<?= $this->extend('customers/layouts/main') ?>
<?= $this->section('content') ?>
<?= csrf_field() ?>
<div class="container">


 <div class="card">
  <div class="card-body">
   <?= form_open('/shopper/requests/update/' . $request['id'], ['id' => 'shopper-edit-form', 'class' => 'form-horizontal']) ?>
   <div class="items" data-items>
    <?php foreach ($items as $i => $item): ?>
     <div class="item" data-item>
      <div class="form-group mb-2 row">
       <h5 class="col-12 text-primary">Item <span class="item-index"><?= $i + 1 ?></span></h5>
      </div>

      <div class="form-group mb-2 row">
       <label class="col-sm-4 col-md-3 col-form-label">Item name<span class="text-danger">*</span></label>
       <div class="col-sm-8 col-md-9">
        <input name="name[]" class="form-control" value="<?= esc($item['name']) ?>" required minlength="2">
       </div>
      </div>

      <div class="form-group mb-2 row">
       <label class="col-sm-4 col-md-3 col-form-label">Item url<span class="text-danger">*</span></label>
       <div class="col-sm-8 col-md-9">
        <input name="url[]" class="form-control" value="<?= esc($item['url']) ?>" required type="url">
       </div>
      </div>

      <div class="form-group mb-2 row">
       <label class="col-sm-4 col-md-3 col-form-label">Item size</label>
       <div class="col-sm-8 col-md-3">
        <input name="size[]" class="form-control" value="<?= esc($item['size']) ?>">
       </div>
       <label class="col-sm-4 col-md-3 col-form-label">Item colour</label>
       <div class="col-sm-8 col-md-3">
        <input name="color[]" class="form-control" value="<?= esc($item['color']) ?>">
       </div>
      </div>

      <div class="form-group mb-2 row">
       <label class="col-sm-4 col-md-3 col-form-label">Additional instructions</label>
       <div class="col-sm-8 col-md-9">
        <textarea name="instructions[]" class="form-control"><?= esc($item['instructions']) ?></textarea>
       </div>
      </div>

      <div class="form-group mb-2 row align-items-center">
       <label class="col-sm-4 col-md-3 col-form-label">Quantity<span class="text-danger">*</span></label>
       <div class="col-sm-4 col-md-4">
        <input name="quantity[]" type="number" min="1" value="<?= esc($item['quantity']) ?>" class="form-control" required>
       </div>
       <div class="col-sm-4 col-md-3">
        <button type="button" class="btn btn-danger btn-block remove-item">Remove item</button>
       </div>
      </div>
     </div>
    <?php endforeach; ?>
   </div>

   <div class="form-group mb-2 row border-top border-bottom py-2">
    <div class="col-12 col-md-4">
     <button type="button" class="add-item btn btn-block btn-info">Add item</button>
    </div>
   </div>

   <div class="form-group row mb-2">
    <label class="col-sm-4 col-md-3 col-form-label">Shipping Option</label>
    <div class="col-sm-8 col-md-9 d-flex align-items-center gap-3">
     <div class="form-check">
      <input
       class="form-check-input"
       type="radio"
       name="delivery_description"
       id="option_economy"
       value="Economy"
       <?= ($request['delivery_description'] ?? '') === 'Economy' ? 'checked' : '' ?>>
      <label class="form-check-label" for="option_economy">
       Economy
      </label>
     </div>
     <div class="form-check">
      <input
       class="form-check-input"
       type="radio"
       name="delivery_description"
       id="option_express"
       value="Express"
       <?= ($request['delivery_description'] ?? '') === 'Express' ? 'checked' : '' ?>>
      <label class="form-check-label" for="option_express">
       Express
      </label>
     </div>
    </div>
   </div>


   <div class="form-group mb-2 row">
    <label class="col-sm-4 col-md-3 col-form-label">Shipping Preference</label>
    <div class="col-sm-8 col-md-9">
     <input name="delivery_notes" class="form-control" value="<?= esc($request['delivery_notes']) ?>">
    </div>
   </div>

   <div class="form-group mb-2 row">
    <div class="col-sm-8 col-md-9 offset-sm-3">
     <input type="checkbox" id="allow-alternate-retailers" name="use_another_retailer" value="yes" <?= $request['use_another_retailer'] == 'yes' ? 'checked' : '' ?>>
     <label for="allow-alternate-retailers">Allow alternate retailers</label>
    </div>
   </div>

   <div class="row justify-content-between">
    <div class="col-sm-6 col-md-4">
     <button type="submit" name="submit" value="submit" class="btn btn-shippex text-uppercase">Submit Request</button>
    </div>
    <div class="col-sm-6 col-md-4 text-end">
     <button type="submit" name="submit" value="save" class="btn btn-shippex-orange text-uppercase">Save for later</button>
    </div>
   </div>

   <?= form_close() ?>
  </div>
 </div>
</div>
<?= $this->section('script') ?>
<script>
 $(function() {
  function reindex() {
   $('[data-item]').each(function(i) {
    $(this).find('.item-index').text(i + 1);
   });
  }

  $('.add-item').on('click', function() {
   var $template = $('[data-item]').first().clone();
   $template.find('input, textarea').each(function() {
    $(this).val($(this).is('[type=number]') ? 1 : '');
   });
   $template.appendTo('[data-items]');
   reindex();
  });

  $(document).on('click', '.remove-item', function() {
   if ($('[data-item]').length === 1) {
    $(this).closest('[data-item]').find('input, textarea').val('');
    $(this).closest('[data-item]').find('input[type=number]').val(1);
   } else {
    $(this).closest('[data-item]').remove();
   }
   reindex();
  });

  reindex();
 });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>