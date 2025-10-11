<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
 <div class="card border-0 shadow-sm">
  <div class="card-header bg-shippex-purple text-white">
   <h3 class="mb-0"><i class="fas fa-warehouse me-2"></i>Edit Warehouse Address</h3>
  </div>

  <div class="card-body">
   <form action="/warehouse/update/<?= $address['id'] ?>" method="post" class="simple-form">
    <div class="row g-3">
     <!-- User Selection -->
     <div class="col-12">
      <label class="form-label text-shippex-purple fw-bold">User</label>
      <select name="user_id" class="form-control form-control-lg" required>
       <?php foreach ($users as $user): ?>
        <option value="<?= $user['id'] ?>" <?= $user['id'] == $address['user_id'] ? 'selected' : '' ?>>
         <?= esc($user['username']) ?> (ID: <?= $user['id'] ?>)
        </option>
       <?php endforeach; ?>
      </select>
     </div>

     <!-- Country -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold"> Country</label>

      <?php
      // Load countries list
      $countries = json_decode(file_get_contents(dirname(__DIR__, 3) . '/views/partials/countries.json'), true);

      // Determine selected country (if available)
      $selectedCountry = isset($address['country']) && !empty($address['country']) ? $address['country'] : '';
      ?>

      <select id="country" name="country" class="form-control form-control-lg" required>
       <option value="">-- Select Country --</option>
       <?php foreach ($countries as $ct): ?>
        <option value="<?= esc($ct['name']) ?>"
         <?= $ct['name'] === $selectedCountry ? 'selected' : '' ?>>
         <?= esc($ct['name']) ?>
        </option>
       <?php endforeach; ?>
      </select>
     </div>
     <!-- City -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold">City</label>
      <input type="text" name="city" class="form-control form-control-lg"
       value="<?= esc($address['city']) ?>" required>
     </div>

     <!-- Address Line -->
     <div class="col-12">
      <label class="form-label text-shippex-purple fw-bold">Address Line</label>
      <textarea name="address_line" class="form-control form-control-lg" rows="3" required><?= esc($address['address_line']) ?></textarea>
     </div>



     <!-- Postal Code -->
     <div class="col-md-6">
      <label class="form-label text-shippex-purple fw-bold">Postal Code</label>
      <input type="text" name="postal_code" class="form-control form-control-lg"
       value="<?= esc($address['postal_code']) ?>" required>
     </div>
     <!-- Phone -->
     <div class="col-12">
      <label class="form-label text-shippex-purple fw-bold">Phone</label>
      <input type="text" name="phone" class="form-control form-control-lg"
       value="<?= esc($address['phone']) ?>" required>
     </div>

     <!-- Default Checkbox -->
     <div class="col-12 mt-2">
      <div class="form-check">
       <input type="checkbox" name="is_default" id="isDefault" value="1"
        class="form-check-input" <?= $address['is_default'] ? 'checked' : '' ?>>
       <label class="form-check-label text-shippex-purple fw-bold" for="isDefault">
        Set as Default Warehouse
       </label>
      </div>
     </div>

     <!-- Action Buttons -->
     <div class="col-12 mt-4">
      <div class="d-flex justify-content-between">
       <a href="/warehouse" class="btn btn-outline-shippex-purple">
        <i class="fas fa-arrow-left me-2"></i>Cancel
       </a>
       <button type="submit" class="btn btn-shippex-orange">
        <i class="fas fa-save me-2"></i>Update Warehouse
       </button>
      </div>
     </div>
    </div>
   </form>
  </div>
 </div>
</div>

<style>
 .bg-shippex-purple {
  background-color: #4E148C !important;
 }

 .text-shippex-purple {
  color: #4E148C !important;
 }

 .btn-shippex-orange {
  background-color: #FF6600;
  color: white;
  border-color: #FF6600;
 }

 .btn-shippex-orange:hover {
  background-color: #e05c00;
  border-color: #e05c00;
  color: white;
 }

 .btn-outline-shippex-purple {
  color: #4E148C;
  border-color: #4E148C;
 }

 .btn-outline-shippex-purple:hover {
  background-color: #4E148C;
  color: white;
 }

 .simple-form .form-control-lg {
  padding: 0.75rem 1rem;
  font-size: 1rem;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
 }

 .simple-form .form-control-lg:focus {
  border-color: #4E148C;
  box-shadow: 0 0 0 0.25rem rgba(78, 20, 140, 0.25);
 }

 .form-check-input:checked {
  background-color: #FF6600;
  border-color: #FF6600;
 }

 .card {
  border-radius: 0.5rem;
 }
</style>

<script>
 // Simple phone number formatting
 document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
  this.value = this.value.replace(/[^0-9+]/g, '');
 });
</script>

<?= $this->endSection() ?>